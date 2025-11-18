const API_BASE = '../api';
const viewContainer = document.getElementById('view-container');
const navButtons = document.querySelectorAll('.bottom-nav button');
const desktopBlocker = document.getElementById('desktop-blocker');
const appShell = document.getElementById('app');
const btnToggleTheme = document.getElementById('btnToggleTheme');
const btnLogout = document.getElementById('btnLogout');

const state = {
    token: localStorage.getItem('vidapro_token') || '',
    user: JSON.parse(localStorage.getItem('vidapro_user') || 'null'),
    currentView: 'home'
};

const authTemplate = () => `
    <section class="section">
        <div class="card">
            <h2>Accede a VidaPro+</h2>
            <form id="formLogin">
                <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Contraseña</label><input type="password" name="password" required></div>
                <button class="primary">Iniciar sesión</button>
            </form>
        </div>
        <div class="card">
            <h2>Crear cuenta</h2>
            <form id="formRegister">
                <div class="form-group"><label>Nombre</label><input name="name" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Contraseña</label><input type="password" name="password" minlength="6" required></div>
                <button class="primary">Registrarme</button>
            </form>
        </div>
    </section>`;

const templates = {
    home: () => `
        <section class="section">
            <div class="card">
                <h2>Hola ${state.user?.name || ''}</h2>
                <p>Revisa tus objetivos y mantente al día.</p>
                <div class="progress-bar"><span style="width:70%"></span></div>
                <small>Metas completadas 70%</small>
            </div>
            <div class="card">
                <h2>Recordatorios rápidos</h2>
                <ul>
                    <li>💧 Agua cada 2 horas</li>
                    <li>🍽️ Planifica tu próxima comida</li>
                    <li>🏋️ Sesión de fuerza por la tarde</li>
                </ul>
            </div>
        </section>`,
    routines: (data=[]) => `
        <section class="section">
            <div class="card">
                <h2>Nueva rutina</h2>
                <form id="formRoutine">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="type">
                            <option value="full body">Full Body</option>
                            <option value="pecho">Pecho</option>
                            <option value="espalda">Espalda</option>
                            <option value="piernas">Piernas</option>
                            <option value="cardio">Cardio</option>
                        </select>
                    </div>
                    <button class="primary">Guardar</button>
                </form>
            </div>
            <div class="card">
                <h2>Rutinas activas</h2>
                ${data.map(r => `<div class="list-item"><span>${r.name}</span><span class="badge">${r.type}</span></div>`).join('') || '<p>No hay rutinas.</p>'}
            </div>
        </section>`,
    meals: (data=[]) => `
        <section class="section">
            <div class="card">
                <h2>Registrar comida</h2>
                <form id="formMeal">
                    <div class="form-group"><label>Nombre</label><input name="name" required></div>
                    <div class="form-group"><label>Calorías</label><input type="number" name="calories" required></div>
                    <div class="form-group"><label>Tipo</label><select name="type"><option>desayuno</option><option>almuerzo</option><option>cena</option><option>snack</option></select></div>
                    <button class="primary">Guardar</button>
                </form>
            </div>
            <div class="card">
                <h2>Hoy</h2>
                ${data.map(m => `<div class="list-item"><span>${m.name}</span><span class="badge">${m.calories} kcal</span></div>`).join('') || '<p>No hay registros.</p>'}
            </div>
        </section>`,
    water: (data) => `
        <section class="section">
            <div class="card">
                <h2>Agua</h2>
                <p>Meta diaria: ${(data?.goal || 2000)} ml</p>
                <div class="progress-bar"><span style="width:${Math.min(100, ((data?.consumed || 0)/(data?.goal||2000))*100)}%"></span></div>
                <p><strong>${data?.consumed || 0} ml</strong> consumidos hoy</p>
                <form id="formWater">
                    <div class="form-group"><label>Agregar ml</label><input type="number" name="amount" required></div>
                    <button class="primary">Registrar</button>
                </form>
            </div>
        </section>`,
    habits: (data=[]) => `
        <section class="section">
            <div class="card">
                <h2>Nuevo hábito</h2>
                <form id="formHabit">
                    <div class="form-group"><label>Nombre</label><input name="name" required></div>
                    <div class="form-group"><label>Frecuencia</label><select name="frequency"><option>diario</option><option>semanal</option></select></div>
                    <button class="primary">Guardar</button>
                </form>
            </div>
            <div class="card">
                <h2>Hábitos</h2>
                ${data.map(h => `<div class="list-item"><span>${h.name}</span><button class="badge" data-complete="${h.id}">Hecho</button></div>`).join('') || '<p>Sin hábitos.</p>'}
            </div>
        </section>`,
    progress: (data={}) => `
        <section class="section">
            <div class="card">
                <h2>Progreso</h2>
                <p>Peso actual: <strong>${data.weight || '--'} kg</strong></p>
                <p>Calorías promedio: <strong>${data.avgCalories || '--'} kcal</strong></p>
                <p>Rutinas completadas esta semana: <strong>${data.routinesCompleted || 0}</strong></p>
            </div>
            <div class="card">
                <h2>Metas</h2>
                <p>Objetivo: ${data.goal || 'Mantener'}</p>
                <p>Nivel: ${data.level || 'Intermedio'}</p>
            </div>
        </section>`
};

function renderView(name, data) {
    state.currentView = name;
    viewContainer.innerHTML = templates[name](data);
    navButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.view === name));
    attachFormHandlers();
}

function renderAuth() {
    viewContainer.innerHTML = authTemplate();
    navButtons.forEach(btn => btn.classList.remove('active'));
    const formLogin = document.getElementById('formLogin');
    const formRegister = document.getElementById('formRegister');
    formLogin.addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = Object.fromEntries(new FormData(formLogin));
        const response = await apiFetch('auth.php?action=login', 'POST', payload, false);
        if (response.token) {
            onAuthSuccess(response);
        } else if (response.error) {
            alert(response.error);
        }
    });
    formRegister.addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = Object.fromEntries(new FormData(formRegister));
        const response = await apiFetch('auth.php?action=register', 'POST', payload, false);
        alert(response.message || response.error || 'Intenta nuevamente');
    });
}

function attachFormHandlers() {
    const formMeal = document.getElementById('formMeal');
    if (formMeal) {
        formMeal.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = Object.fromEntries(new FormData(formMeal));
            await apiFetch('meals.php', 'POST', payload);
            loadData('meals');
        });
    }
    const formRoutine = document.getElementById('formRoutine');
    if (formRoutine) {
        formRoutine.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = Object.fromEntries(new FormData(formRoutine));
            await apiFetch('routines.php', 'POST', payload);
            loadData('routines');
        });
    }
    const formWater = document.getElementById('formWater');
    if (formWater) {
        formWater.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = Object.fromEntries(new FormData(formWater));
            await apiFetch('water.php', 'POST', payload);
            loadData('water');
        });
    }
    const formHabit = document.getElementById('formHabit');
    if (formHabit) {
        formHabit.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = Object.fromEntries(new FormData(formHabit));
            await apiFetch('habits.php', 'POST', payload);
            loadData('habits');
        });
    }
    document.querySelectorAll('[data-complete]').forEach(btn => {
        btn.addEventListener('click', async () => {
            await apiFetch('habits.php', 'PATCH', { id: btn.dataset.complete, complete: true });
            loadData('habits');
        });
    });
}

async function apiFetch(endpoint, method = 'GET', body, includeAuth = true) {
    const opts = {
        method,
        headers: {
            'Content-Type': 'application/json',
        },
    };
    if (includeAuth && state.token) {
        opts.headers['Authorization'] = `Bearer ${state.token}`;
    }
    if (body) {
        opts.body = JSON.stringify(body);
    }
    const res = await fetch(`${API_BASE}/${endpoint}`, opts);
    if (res.status === 401) {
        handleLogout(false);
        return {};
    }
    return res.json();
}

async function loadData(view) {
    if (!state.token) {
        renderAuth();
        return;
    }
    switch (view) {
        case 'meals': {
            const meals = await apiFetch('meals.php');
            renderView('meals', meals.items || []);
            break;
        }
        case 'routines': {
            const routines = await apiFetch('routines.php');
            renderView('routines', routines.items || []);
            break;
        }
        case 'water': {
            const water = await apiFetch('water.php');
            renderView('water', water);
            break;
        }
        case 'habits': {
            const habits = await apiFetch('habits.php');
            renderView('habits', habits.items || []);
            break;
        }
        case 'progress': {
            const progress = await apiFetch('progress.php');
            renderView('progress', progress);
            break;
        }
        default:
            renderView('home');
    }
}

function onAuthSuccess(response) {
    state.token = response.token;
    state.user = response.user;
    localStorage.setItem('vidapro_token', state.token);
    localStorage.setItem('vidapro_user', JSON.stringify(state.user));
    loadData(state.currentView);
}

function setupNavigation() {
    navButtons.forEach(btn => btn.addEventListener('click', () => loadData(btn.dataset.view)));
}

function enforceMobileOnly() {
    const shouldBlock = window.innerWidth > 900;
    desktopBlocker.classList.toggle('hidden', !shouldBlock);
    appShell.style.display = shouldBlock ? 'none' : 'flex';
}

function toggleTheme() {
    document.documentElement.classList.toggle('dark');
}

function registerServiceWorker() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('sw.js');
    }
}

async function requestNotifications() {
    if (!('Notification' in window)) return;
    const permission = await Notification.requestPermission();
    if (permission === 'granted') {
        scheduleReminders();
    }
}

function scheduleReminders() {
    setInterval(() => {
        navigator.serviceWorker.ready.then(reg => {
            reg.showNotification('Hora de hidratarse 💧', {
                body: 'Agrega un registro de agua en VidaPro+',
                icon: 'icons/icon-192.svg'
            });
        });
    }, 1000 * 60 * 120);
}

function handleLogout(triggerApi = true) {
    const token = state.token;
    state.token = '';
    state.user = null;
    localStorage.removeItem('vidapro_token');
    localStorage.removeItem('vidapro_user');
    if (triggerApi && token) {
        fetch(`${API_BASE}/auth.php?action=logout`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` }
        });
    }
    renderAuth();
}

function init() {
    setupNavigation();
    enforceMobileOnly();
    if (state.token) {
        loadData('home');
    } else {
        renderAuth();
    }
    registerServiceWorker();
    requestNotifications();
}

window.addEventListener('resize', enforceMobileOnly);
btnToggleTheme.addEventListener('click', toggleTheme);
btnLogout.addEventListener('click', () => handleLogout());

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
