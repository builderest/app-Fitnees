const API_BASE = '../api';
const state = {
    token: localStorage.getItem('fitlife_token'),
    user: null,
    view: 'view-home',
    routines: [],
    exercises: [],
    meals: [],
    goals: null,
    progress: {
        peso: [],
        entrenos: [],
        calorias: []
    },
    timer: {
        interval: null,
        seconds: 0,
        running: false
    }
};

const qs = (sel) => document.querySelector(sel);
const qsa = (sel) => document.querySelectorAll(sel);

function toggleWarning() {
    const warning = qs('#screen-warning');
    if (window.innerWidth > 900) {
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
}

window.addEventListener('resize', toggleWarning);
toggleWarning();

async function apiRequest(endpoint, options = {}) {
    const headers = options.headers || {};
    headers['Content-Type'] = 'application/json';
    if (state.token) {
        headers['Authorization'] = `Bearer ${state.token}`;
    }
    const response = await fetch(`${API_BASE}/${endpoint}`, {
        ...options,
        headers
    });
    if (response.status === 204) return null;
    const data = await response.json().catch(() => ({ error: 'Respuesta inválida' }));
    if (!response.ok) {
        throw new Error(data.error || 'Error desconocido');
    }
    return data;
}

function showView(viewId) {
    state.view = viewId;
    qsa('.view').forEach((view) => view.classList.toggle('active', view.id === viewId));
    qsa('.bottom-nav button').forEach((btn) => btn.classList.toggle('active', btn.dataset.target === viewId));
}

async function handleLogin(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    try {
        const data = await apiRequest('auth.php?action=login', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData))
        });
        state.token = data.token;
        localStorage.setItem('fitlife_token', data.token);
        await bootstrapApp();
    } catch (error) {
        alert(error.message);
    }
}

async function handleRegister(event) {
    event.preventDefault();
    const payload = Object.fromEntries(new FormData(event.target));
    try {
        await apiRequest('auth.php?action=register', {
            method: 'POST',
            body: JSON.stringify(payload)
        });
        alert('Cuenta creada. Ahora inicia sesión.');
        event.target.reset();
    } catch (error) {
        alert(error.message);
    }
}

async function bootstrapApp() {
    try {
        state.user = await apiRequest('users.php');
        qs('#userName').textContent = state.user.name;
        qs('#authScreen').classList.add('hidden');
        qs('#appShell').classList.remove('hidden');
        await Promise.all([
            loadDashboard(),
            loadExercises(),
            loadRoutines(),
            loadMeals(),
            loadGoals(),
            loadProgress()
        ]);
        requestNotificationPermission();
    } catch (error) {
        console.error(error);
        qs('#authScreen').classList.remove('hidden');
        qs('#appShell').classList.add('hidden');
    }
}

function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

function renderDashboard(data) {
    const cards = [
        { label: 'Calorías recomendadas', value: `${data.calorias} kcal`, icon: '🔥' },
        { label: 'Tiempo activo', value: `${data.minutosActivos} min`, icon: '⏱️' },
        { label: 'Hidratación', value: `${data.agua}%`, icon: '💧' }
    ];
    qs('#dashboardCards').innerHTML = cards.map(card => `
        <article class="card">
            <span>${card.icon}</span>
            <h3>${card.value}</h3>
            <p class="muted">${card.label}</p>
        </article>
    `).join('');

    qs('#todayRoutine').innerHTML = `
        <h3>Rutina destacada</h3>
        <p>${data.rutina.nombre}</p>
        <div class="progress-bar"><span style="width:${data.rutina.progreso}%"></span></div>
        <small class="muted">${data.rutina.progreso}% completado</small>
    `;

    qs('#hydrationCard').innerHTML = `
        <h3>Agua de hoy</h3>
        <p>${data.aguaActual} ml / ${data.metaAgua} ml</p>
        <button class="ghost" id="btnRecordWater">Registrar 250 ml</button>
    `;
    qs('#btnRecordWater').addEventListener('click', () => alert('Recuerda registrar tu agua desde la app móvil.'));
}

async function loadDashboard() {
    const data = await apiRequest('users.php?action=dashboard');
    renderDashboard(data);
}

async function loadExercises() {
    const query = qs('#exerciseSearch').value || '';
    const data = await apiRequest(`ejercicios.php${query ? `?search=${encodeURIComponent(query)}` : ''}`);
    state.exercises = data.items;
    const list = qs('#exerciseList');
    list.innerHTML = state.exercises.map(item => `
        <article class="card exercise-card">
            <img src="${item.image}" alt="${item.name}">
            <div>
                <h3>${item.name}</h3>
                <small class="muted">${item.category} • ${item.level}</small>
            </div>
            <p>${item.description}</p>
            <div class="exercise-animation"></div>
            <button data-id="${item.id}" class="ghost btnAddToRoutine">Agregar a rutina</button>
        </article>
    `).join('');
    list.querySelectorAll('.btnAddToRoutine').forEach(btn => btn.addEventListener('click', () => alert('Selecciona la rutina a editar en el panel de rutinas.')));
}

async function loadRoutines() {
    const data = await apiRequest('rutinas.php');
    state.routines = data.items;
    const list = qs('#routineList');
    if (!state.routines.length) {
        list.innerHTML = `<article class="card"><img src="assets/images/ui/empty-state.svg" alt="Vacío"><p>No tienes rutinas, crea la primera.</p></article>`;
        return;
    }
    list.innerHTML = state.routines.map(r => `
        <article class="card">
            <div>
                <h3>${r.name}</h3>
                <small class="muted">${r.goal} • ${r.level} • ${r.days_per_week} días</small>
            </div>
            <p>${r.description || ''}</p>
            <div class="progress-bar"><span style="width:${r.progress}%"></span></div>
            <button class="ghost" data-id="${r.id}" data-action="play">Iniciar sesión</button>
        </article>
    `).join('');
    list.querySelectorAll('button[data-action="play"]').forEach(btn => btn.addEventListener('click', () => startRoutineTimer(btn.dataset.id)));
}

async function loadMeals() {
    const data = await apiRequest('alimentacion.php');
    state.meals = data.items;
    const total = state.meals.reduce((sum, meal) => sum + Number(meal.calories), 0);
    qs('#calorieSummary').innerHTML = `
        <h3>Calorías de hoy</h3>
        <p>${total} / ${data.meta || 0} kcal</p>
        <div class="progress-bar"><span style="width:${Math.min(100, (total / (data.meta || 1)) * 100)}%"></span></div>
    `;
    const list = qs('#mealList');
    list.innerHTML = state.meals.map(meal => `
        <article class="card meal-card">
            <div>
                <strong>${meal.name}</strong>
                <small class="muted">${meal.type}</small>
            </div>
            <p>${meal.calories} kcal</p>
        </article>
    `).join('');
}

async function loadGoals() {
    const data = await apiRequest('metas.php');
    state.goals = data.goal;
    qs('#goalCard').innerHTML = `
        <h3>Meta principal</h3>
        <p>${data.goal?.main_goal || 'Configura tus metas'}</p>
        <div class="progress-bar"><span style="width:${data.goal?.progress || 0}%"></span></div>
        <small class="muted">Peso actual: ${data.goal?.current_weight || '--'} kg</small>
    `;
    qs('#profileCard').innerHTML = `
        <h3>Perfil</h3>
        <p>${state.user.name}</p>
        <p>${state.user.email}</p>
        <p>Nivel: ${state.user.level}</p>
    `;
}

async function loadProgress() {
    const [peso, entrenos, calorias] = await Promise.all([
        apiRequest('progreso.php?type=peso'),
        apiRequest('progreso.php?type=entrenos'),
        apiRequest('progreso.php?type=calorias')
    ]);
    state.progress = { peso, entrenos, calorias };
    renderCharts();
}

function renderCharts() {
    const ctxPeso = document.getElementById('chartPeso');
    const ctxEntrenos = document.getElementById('chartEntrenos');
    const ctxCalorias = document.getElementById('chartCalorias');
    new Chart(ctxPeso, {
        type: 'line',
        data: {
            labels: state.progress.peso.labels,
            datasets: [{
                label: 'Peso (kg)',
                borderColor: '#0b8c7a',
                data: state.progress.peso.data,
                tension: 0.4,
                fill: false
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
    new Chart(ctxEntrenos, {
        type: 'bar',
        data: {
            labels: state.progress.entrenos.labels,
            datasets: [{
                label: 'Rutinas completadas',
                backgroundColor: '#ff8a65',
                data: state.progress.entrenos.data
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
    new Chart(ctxCalorias, {
        type: 'line',
        data: {
            labels: state.progress.calorias.labels,
            datasets: [{
                label: 'Calorías',
                borderColor: '#00796b',
                data: state.progress.calorias.data,
                fill: false
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

function startRoutineTimer(routineId) {
    const routine = state.routines.find(r => r.id === Number(routineId));
    if (!routine) return;
    let pointer = 0;
    state.timer.seconds = routine.exercises?.[pointer]?.duration || 60;
    updateTimerDisplay();
    clearInterval(state.timer.interval);
    state.timer.running = true;
    const beep = document.getElementById('beepAudio');
    state.timer.interval = setInterval(() => {
        if (state.timer.seconds === 0) {
            beep.currentTime = 0;
            beep.play().catch(() => {});
            pointer = (pointer + 1) % (routine.exercises?.length || 1);
            state.timer.seconds = routine.exercises?.[pointer]?.duration || 60;
        } else {
            state.timer.seconds -= 1;
        }
        updateTimerDisplay();
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = String(Math.floor(state.timer.seconds / 60)).padStart(2, '0');
    const seconds = String(state.timer.seconds % 60).padStart(2, '0');
    qs('#timerDisplay').textContent = `${minutes}:${seconds}`;
}

function pauseTimer() {
    clearInterval(state.timer.interval);
    state.timer.running = false;
}

function resetTimer() {
    pauseTimer();
    state.timer.seconds = 0;
    updateTimerDisplay();
}

function setupTimerControls() {
    qs('#btnTimerStart').addEventListener('click', () => {
        if (!state.routines.length) return alert('Crea una rutina primero.');
        startRoutineTimer(state.routines[0].id);
    });
    qs('#btnTimerPause').addEventListener('click', pauseTimer);
    qs('#btnTimerReset').addEventListener('click', resetTimer);
}

function setupNavigation() {
    qs('#bottomNav').addEventListener('click', (event) => {
        const target = event.target.closest('button');
        if (!target) return;
        showView(target.dataset.target);
    });
}

function setupThemeToggle() {
    const stored = localStorage.getItem('fitlife_theme');
    if (stored === 'dark') document.body.classList.add('dark');
    qs('#btnToggleTheme').addEventListener('click', () => {
        document.body.classList.toggle('dark');
        localStorage.setItem('fitlife_theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    });
}

function setupAuth() {
    qs('#loginForm').addEventListener('submit', handleLogin);
    qs('#registerForm').addEventListener('submit', handleRegister);
    qs('#btnLogout').addEventListener('click', async () => {
        await apiRequest('auth.php?action=logout', { method: 'POST' }).catch(() => {});
        localStorage.removeItem('fitlife_token');
        state.token = null;
        qs('#appShell').classList.add('hidden');
        qs('#authScreen').classList.remove('hidden');
    });
}

function setupSearch() {
    qs('#exerciseSearch').addEventListener('input', debounce(loadExercises, 400));
}

function debounce(fn, wait = 300) {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(null, args), wait);
    };
}

function setupHydrationShortcut() {
    qs('#btnAddMeal').addEventListener('click', () => alert('Registra tus comidas desde la sección de alimentación.'));
    qs('#btnAddRoutine').addEventListener('click', () => alert('Usa el panel de rutinas para crear una nueva sesión.'));
}

setupNavigation();
setupTimerControls();
setupThemeToggle();
setupAuth();
setupSearch();
setupHydrationShortcut();

if (state.token) {
    bootstrapApp();
}

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('sw.js').catch(() => {});
}
