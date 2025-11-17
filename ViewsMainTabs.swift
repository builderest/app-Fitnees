import SwiftUI
import CoreData
import Charts

// MARK: - Root TabView containing five main sections
struct RootTabView: View {
    @Environment(\.managedObjectContext) private var context
    @EnvironmentObject private var notificationManager: NotificationManager

    var body: some View {
        TabView {
            HomeView(viewModel: HomeViewModel(context: context))
                .tabItem { Label("Inicio", systemImage: "house.fill") }
            RoutinesView(viewModel: RoutineViewModel(context: context))
                .tabItem { Label("Rutinas", systemImage: "dumbbell.fill") }
            NutritionView(viewModel: NutritionViewModel(context: context))
                .tabItem { Label("Comidas", systemImage: "fork.knife") }
            HabitsView(viewModel: HabitViewModel(context: context, notificationManager: notificationManager))
                .tabItem { Label("Hábitos", systemImage: "drop.fill") }
            ProgressDashboardView(viewModel: ProgressViewModel(context: context))
                .tabItem { Label("Progreso", systemImage: "chart.line.uptrend.xyaxis") }
        }
        .accentColor(.green)
        .onAppear { notificationManager.requestAuthorization() }
    }
}

// MARK: - Inicio
struct HomeView: View {
    @Environment(\.managedObjectContext) private var context
    @ObservedObject var viewModel: HomeViewModel
    @State private var showGoalSheet = false

    var body: some View {
        NavigationStack {
            ScrollView {
                VStack(spacing: 20) {
                    headerCard
                    metricsGrid
                    quoteCard
                }
                .padding()
            }
            .navigationTitle("VidaPro+")
            .toolbar {
                ToolbarItem(placement: .navigationBarTrailing) {
                    Button(action: { showGoalSheet = true }) {
                        Image(systemName: "target")
                    }
                }
            }
            .sheet(isPresented: $showGoalSheet) {
                GoalsView(viewModel: GoalViewModel(context: context))
            }
        }
    }

    private var headerCard: some View {
        VStack(alignment: .leading, spacing: 16) {
            Text("Progreso de hoy")
                .font(.title2).bold()
            HStack {
                progressRing(title: "Pasos", value: Double(viewModel.steps), goal: 10000)
                progressRing(title: "Calorías", value: viewModel.manualCalories, goal: 500)
                progressRing(title: "Ejercicio", value: viewModel.exerciseGoalMinutes, goal: 60)
            }
        }
        .padding()
        .background(VidaTheme.cardBackground)
        .clipShape(RoundedRectangle(cornerRadius: 24))
    }

    private var metricsGrid: some View {
        LazyVGrid(columns: [GridItem(.flexible()), GridItem(.flexible())], spacing: 16) {
            metricTile(title: "Agua", value: "\(Int(viewModel.waterConsumedToday)) ml", icon: "drop.fill", color: .blue)
            metricTile(title: "Meta", value: "30 min ejercicio", icon: "flame.fill", color: .orange)
            metricTile(title: "Pasos", value: "\(viewModel.steps)", icon: "figure.walk", color: .green)
            metricTile(title: "Calorías", value: String(format: "%.0f kcal", viewModel.manualCalories), icon: "bolt.heart", color: .pink)
        }
    }

    private var quoteCard: some View {
        VStack(alignment: .leading, spacing: 12) {
            Text("Motivación del día")
                .font(.headline)
            Text(viewModel.quote)
                .font(.title3)
                .bold()
        }
        .padding()
        .frame(maxWidth: .infinity, alignment: .leading)
        .background(LinearGradient(colors: [.purple.opacity(0.8), .blue], startPoint: .topLeading, endPoint: .bottomTrailing))
        .foregroundColor(.white)
        .clipShape(RoundedRectangle(cornerRadius: 24))
    }

    private func progressRing(title: String, value: Double, goal: Double) -> some View {
        VStack {
            ZStack {
                Circle().stroke(Color.gray.opacity(0.2), lineWidth: 8)
                Circle()
                    .trim(from: 0, to: min(value/goal, 1))
                    .stroke(AngularGradient(colors: [.green, .blue], center: .center), style: StrokeStyle(lineWidth: 10, lineCap: .round))
                    .rotationEffect(.degrees(-90))
                    .animation(.easeInOut, value: value)
                Text("\(Int((value/goal)*100))%")
                    .font(.headline)
            }
            Text(title).font(.caption)
        }
    }

    private func metricTile(title: String, value: String, icon: String, color: Color) -> some View {
        VStack(alignment: .leading, spacing: 8) {
            Label(title, systemImage: icon)
                .font(.headline)
                .foregroundColor(color)
            Text(value)
                .font(.title2).bold()
        }
        .padding()
        .frame(maxWidth: .infinity, alignment: .leading)
        .background(VidaTheme.cardBackground)
        .clipShape(RoundedRectangle(cornerRadius: 20))
    }
}

// MARK: - Rutinas
struct RoutinesView: View {
    @ObservedObject var viewModel: RoutineViewModel
    @State private var newRoutineName = ""
    @State private var showTemplate = false

    private let categories = ["Pecho", "Espalda", "Piernas", "Glúteos", "Hombros", "Brazos", "Full Body", "Cardio"]

    var body: some View {
        NavigationStack {
            VStack {
                Picker("Categoría", selection: $viewModel.selectedCategory) {
                    ForEach(categories, id: \.self) { Text($0) }
                }
                .pickerStyle(.segmented)
                .padding()

                List {
                    Section("Crear nueva rutina") {
                        TextField("Nombre", text: $newRoutineName)
                        Button("Guardar") {
                            guard !newRoutineName.isEmpty else { return }
                            viewModel.addRoutine(name: newRoutineName, category: viewModel.selectedCategory)
                            newRoutineName = ""
                        }
                    }

                    Section("Plantillas IA") {
                        ForEach(RoutineTemplate.templates, id: \.name) { template in
                            Button(action: { viewModel.addTemplate(template) }) {
                                VStack(alignment: .leading) {
                                    Text(template.name).bold()
                                    Text(template.category).font(.caption)
                                }
                            }
                        }
                    }

                    Section("Rutinas guardadas") {
                        ForEach(viewModel.routines.filter { $0.category == viewModel.selectedCategory }) { routine in
                            NavigationLink(destination: RoutineDetailView(routine: routine)) {
                                VStack(alignment: .leading) {
                                    Text(routine.name).font(.headline)
                                    Text("Ejercicios: \(routine.exerciseArray.count)").font(.caption)
                                }
                            }
                        }
                        .onDelete { indexSet in
                            let routines = viewModel.routines.filter { $0.category == viewModel.selectedCategory }
                            indexSet.forEach { idx in
                                viewModel.delete(routine: routines[idx])
                            }
                        }
                    }
                }
            }
            .navigationTitle("Rutinas")
        }
    }
}

struct RoutineDetailView: View {
    var routine: Routine
    @Environment(\.managedObjectContext) private var context
    @State private var showingTimer = false
    @State private var timerValue: Int = 60

    var body: some View {
        List {
            Section("Ejercicios") {
                ForEach(routine.exerciseArray) { exercise in
                    VStack(alignment: .leading) {
                        Text(exercise.name).bold()
                        Text("Sets: \(exercise.sets)  Reps: \(exercise.reps)  Peso: \(Int(exercise.weight))kg")
                        if exercise.duration > 0 {
                            Button("Iniciar timer \(Int(exercise.duration))s") {
                                timerValue = Int(exercise.duration)
                                showingTimer = true
                            }
                        }
                    }
                    .padding(.vertical, 4)
                }
            }
            Section {
                Toggle("Notificar para entrenar", isOn: Binding(
                    get: { routine.notify },
                    set: { newValue in
                        routine.notify = newValue
                        DataController.shared.save(context: context)
                    }
                ))
            }
        }
        .navigationTitle(routine.name)
        .sheet(isPresented: $showingTimer) {
            ExerciseTimerView(duration: timerValue)
        }
    }
}

struct ExerciseTimerView: View {
    let duration: Int
    @Environment(\.dismiss) private var dismiss
    @State private var remaining: Int = 0
    @State private var timer: Timer?

    var body: some View {
        VStack(spacing: 24) {
            Text("Timer de ejercicio").font(.title2)
            Text("\(remaining)s").font(.system(size: 72, weight: .bold))
            Button("Cerrar") { dismiss() }
                .buttonStyle(.borderedProminent)
        }
        .onAppear {
            remaining = duration
            timer?.invalidate()
            timer = Timer.scheduledTimer(withTimeInterval: 1, repeats: true) { t in
                if remaining > 0 {
                    remaining -= 1
                } else {
                    t.invalidate()
                }
            }
        }
        .onDisappear { timer?.invalidate() }
        .padding()
    }
}

// MARK: - Nutrición
struct NutritionView: View {
    @ObservedObject var viewModel: NutritionViewModel
    @State private var mealName = ""
    @State private var calories = ""
    @State private var selectedType = "Desayuno"
    private let healthyFoods = ["Avena con frutos rojos", "Tazón de quinoa", "Ensalada mediterránea", "Smoothie verde", "Yogurt griego"]

    var body: some View {
        NavigationStack {
            VStack(spacing: 16) {
                VStack(alignment: .leading) {
                    Text("Calorías del día").font(.headline)
                    Text("\(Int(viewModel.totalCaloriesToday)) kcal").font(.largeTitle).bold()
                }
                .frame(maxWidth: .infinity, alignment: .leading)
                .padding()
                .background(VidaTheme.cardBackground)
                .clipShape(RoundedRectangle(cornerRadius: 20))

                Form {
                    Section("Agregar comida") {
                        TextField("Nombre", text: $mealName)
                        TextField("Calorías", text: $calories)
                            .keyboardType(.numberPad)
                        Picker("Tipo", selection: $selectedType) {
                            ForEach(viewModel.mealTypes, id: \.self, content: Text.init)
                        }
                        Button("Guardar") {
                            guard let cal = Double(calories) else { return }
                            viewModel.addMeal(name: mealName, type: selectedType, calories: cal, protein: 20, carbs: 30, fats: 10)
                            mealName = ""
                            calories = ""
                        }
                    }

                    Section("Comidas registradas") {
                        ForEach(viewModel.meals) { meal in
                            HStack {
                                VStack(alignment: .leading) {
                                    Text(meal.name).bold()
                                    Text(meal.type).font(.caption)
                                    Text(String(format: "%.0f kcal", meal.calories))
                                }
                                Spacer()
                                VStack(alignment: .trailing) {
                                    Text("P: \(Int(meal.protein))g")
                                    Text("C: \(Int(meal.carbs))g")
                                    Text("G: \(Int(meal.fats))g")
                                }
                            }
                        }
                        .onDelete { indexSet in
                            indexSet.forEach { viewModel.deleteMeal(viewModel.meals[$0]) }
                        }
                    }

                    Section("Ideas saludables") {
                        ForEach(healthyFoods, id: \.self) { food in
                            Label(food, systemImage: "leaf")
                        }
                    }
                }
            }
            .padding()
            .navigationTitle("Nutrición")
        }
    }
}

// MARK: - Agua y Hábitos
struct HabitsView: View {
    @ObservedObject var viewModel: HabitViewModel
    @State private var newHabit = ""
    @State private var reminderHour = 8
    @State private var reminderMinute = 0

    var body: some View {
        NavigationStack {
            ScrollView {
                VStack(spacing: 20) {
                    waterCard
                    habitList
                }
                .padding()
            }
            .navigationTitle("Hábitos")
        }
    }

    private var waterCard: some View {
        VStack(alignment: .leading, spacing: 16) {
            Text("Agua diaria").font(.headline)
            Text("Meta: \(Int(viewModel.waterGoal)) ml").font(.caption)
            ProgressView(value: viewModel.todayWater, total: viewModel.waterGoal)
            Text("\(Int(viewModel.todayWater)) ml consumidos")
            HStack {
                Button("+250ml") { viewModel.logWater(amount: 250) }
                    .buttonStyle(.bordered)
                Button("+500ml") { viewModel.logWater(amount: 500) }
                    .buttonStyle(.borderedProminent)
            }
        }
        .padding()
        .background(VidaTheme.cardBackground)
        .clipShape(RoundedRectangle(cornerRadius: 20))
    }

    private var habitList: some View {
        VStack(alignment: .leading, spacing: 12) {
            Text("Hábitos saludables").font(.title3).bold()
            ForEach(viewModel.habits) { habit in
                HStack {
                    VStack(alignment: .leading) {
                        Text(habit.name).bold()
                        Text("Recordatorio: \(habit.reminderHour):\(String(format: "%02d", habit.reminderMinute))").font(.caption)
                    }
                    Spacer()
                    Button(action: { viewModel.toggleHabit(habit) }) {
                        Image(systemName: habit.completed ? "checkmark.circle.fill" : "circle")
                            .foregroundColor(.green)
                            .font(.title2)
                    }
                }
                .padding()
                .background(VidaTheme.cardBackground)
                .clipShape(RoundedRectangle(cornerRadius: 16))
            }

            VStack(alignment: .leading, spacing: 8) {
                TextField("Nuevo hábito", text: $newHabit)
                HStack {
                    Stepper("Hora: \(reminderHour)h", value: $reminderHour, in: 0...23)
                    Stepper("Min: \(reminderMinute)", value: $reminderMinute, in: 0...59)
                }
                Button("Guardar hábito") {
                    guard !newHabit.isEmpty else { return }
                    viewModel.addHabit(name: newHabit, hour: reminderHour, minute: reminderMinute)
                    newHabit = ""
                }
            }
            .padding()
            .background(VidaTheme.cardBackground)
            .clipShape(RoundedRectangle(cornerRadius: 16))
        }
    }
}

// MARK: - Progreso
struct ProgressDashboardView: View {
    @ObservedObject var viewModel: ProgressViewModel
    @State private var showingAdd = false
    @State private var showProfile = false
    @Environment(\.managedObjectContext) private var context

    var body: some View {
        NavigationStack {
            List {
                Section("Peso corporal") {
                    Chart(viewModel.data(for: "Peso")) { record in
                        LineMark(x: .value("Fecha", record.date), y: .value("Kg", record.value))
                    }
                    .frame(height: 200)
                }

                Section("Calorías") {
                    Chart {
                        ForEach(viewModel.data(for: "Consumidas")) { record in
                            BarMark(x: .value("Fecha", record.date), y: .value("Consumidas", record.value))
                                .foregroundStyle(.blue)
                        }
                        ForEach(viewModel.data(for: "Quemadas")) { record in
                            BarMark(x: .value("Fecha", record.date), y: .value("Quemadas", record.value))
                                .foregroundStyle(.green)
                        }
                    }
                    .frame(height: 220)
                }

                Section("Agua semanal") {
                    Chart(viewModel.data(for: "Agua")) { record in
                        AreaMark(x: .value("Fecha", record.date), y: .value("ml", record.value))
                            .foregroundStyle(.blue.opacity(0.4))
                    }
                    .frame(height: 180)
                }

                Section("Sueño") {
                    Chart(viewModel.data(for: "Sueño")) { record in
                        LineMark(x: .value("Fecha", record.date), y: .value("Horas", record.value))
                            .foregroundStyle(.purple)
                    }
                    .frame(height: 180)
                }

                Section("Logros") {
                    ForEach(progressBadges(), id: \.self) { badge in
                        Label(badge, systemImage: "star.circle.fill")
                    }
                }
            }
            .navigationTitle("Progreso")
            .toolbar {
                Button(action: { showingAdd = true }) {
                    Image(systemName: "plus")
                }
                Button(action: { showProfile = true }) {
                    Image(systemName: "person.crop.circle")
                }
            }
            .sheet(isPresented: $showingAdd) {
                AddProgressRecordView(viewModel: viewModel)
            }
            .sheet(isPresented: $showProfile) {
                ProfileSettingsView(viewModel: ProfileViewModel(context: context))
            }
        }
    }

    private func progressBadges() -> [String] {
        ["1 semana disciplinado", "30 días de agua", "PR de peso", "Meta alcanzada"]
    }
}

struct AddProgressRecordView: View {
    @ObservedObject var viewModel: ProgressViewModel
    @Environment(\.dismiss) private var dismiss
    @State private var metric = "Peso"
    @State private var value = ""

    let metrics = ["Peso", "Consumidas", "Quemadas", "Agua", "Sueño", "Músculo", "Grasa"]

    var body: some View {
        NavigationStack {
            Form {
                Picker("Métrica", selection: $metric) {
                    ForEach(metrics, id: \.self, content: Text.init)
                }
                TextField("Valor", text: $value)
                    .keyboardType(.decimalPad)
            }
            .navigationTitle("Nuevo registro")
            .toolbar {
                ToolbarItem(placement: .cancellationAction) {
                    Button("Cerrar", action: { dismiss() })
                }
                ToolbarItem(placement: .confirmationAction) {
                    Button("Guardar") {
                        guard let val = Double(value) else { return }
                        viewModel.addRecord(metric: metric, value: val)
                        dismiss()
                    }
                }
            }
        }
    }
}
