import Foundation
import CoreData
import SwiftUI

// MARK: - Utility generators
struct MotivationalQuotes {
    static let all: [String] = [
        "Cada paso cuenta, incluso los pequeños.",
        "Construye tu fortaleza un día a la vez.",
        "Eres tu mejor inversión.",
        "El esfuerzo de hoy es el logro de mañana.",
        "Respira, enfócate y continúa." ]
}

struct RoutineTemplate {
    let name: String
    let category: String
    let exercises: [(String, Int, Int, Double, Double)]

    static let templates: [RoutineTemplate] = [
        RoutineTemplate(name: "Pecho Poderoso", category: "Pecho", exercises: [
            ("Press banca", 4, 10, 40, 0),
            ("Aperturas mancuernas", 3, 12, 12, 0),
            ("Flexiones", 3, 15, 0, 0)
        ]),
        RoutineTemplate(name: "Piernas Explosivas", category: "Piernas", exercises: [
            ("Sentadilla", 5, 8, 60, 0),
            ("Prensa", 4, 12, 90, 0),
            ("Zancadas", 3, 16, 20, 0)
        ]),
        RoutineTemplate(name: "Cardio HIIT", category: "Cardio", exercises: [
            ("Sprint", 6, 0, 0, 30),
            ("Descanso", 6, 0, 0, 30),
            ("Burpees", 4, 12, 0, 0)
        ])
    ]
}

// MARK: - Home
final class HomeViewModel: ObservableObject {
    @Published var steps: Int = 5200
    @Published var manualCalories: Double = 320
    @Published var exerciseGoalMinutes: Double = 30
    @Published var quote: String = MotivationalQuotes.all.randomElement() ?? "Listo para mejorar"
    @Published var waterConsumedToday: Double = 0

    private let context: NSManagedObjectContext

    init(context: NSManagedObjectContext) {
        self.context = context
        fetchTodayWater()
    }

    func fetchTodayWater() {
        let request: NSFetchRequest<WaterLog> = WaterLog.fetchRequest()
        let start = Calendar.current.startOfDay(for: Date())
        request.predicate = NSPredicate(format: "date >= %@", start as NSDate)
        do {
            waterConsumedToday = try context.fetch(request).reduce(0) { $0 + $1.amountML }
        } catch {
            waterConsumedToday = 0
        }
    }

    func addManualCalorieBurn(amount: Double) {
        manualCalories += amount
    }
}

// MARK: - Routine ViewModel
final class RoutineViewModel: ObservableObject {
    @Published var selectedCategory: String = "Pecho"
    @Published var routines: [Routine] = []

    private let context: NSManagedObjectContext
    private let dataController = DataController.shared

    init(context: NSManagedObjectContext) {
        self.context = context
        fetch()
    }

    func fetch() {
        let request: NSFetchRequest<Routine> = Routine.fetchRequest()
        do {
            routines = try context.fetch(request)
        } catch {
            routines = []
        }
    }

    func addTemplate(_ template: RoutineTemplate) {
        let routine = Routine(context: context)
        routine.id = UUID()
        routine.name = template.name
        routine.category = template.category
        routine.createdAt = Date()
        routine.notify = false

        template.exercises.forEach { data in
            let exercise = Exercise(context: context)
            exercise.id = UUID()
            exercise.name = data.0
            exercise.sets = Int16(data.1)
            exercise.reps = Int16(data.2)
            exercise.weight = data.3
            exercise.duration = data.4
            exercise.createdAt = Date()
            exercise.routine = routine
        }
        dataController.save(context: context)
        fetch()
    }

    func addRoutine(name: String, category: String) {
        let routine = Routine(context: context)
        routine.id = UUID()
        routine.name = name
        routine.category = category
        routine.createdAt = Date()
        routine.notify = false
        dataController.save(context: context)
        fetch()
    }

    func delete(routine: Routine) {
        context.delete(routine)
        dataController.save(context: context)
        fetch()
    }
}

// MARK: - Nutrition ViewModel
final class NutritionViewModel: ObservableObject {
    @Published var meals: [Meal] = []
    @Published var totalCaloriesToday: Double = 0
    private let context: NSManagedObjectContext
    private let dataController = DataController.shared

    let mealTypes = ["Desayuno", "Almuerzo", "Cena", "Snacks"]

    init(context: NSManagedObjectContext) {
        self.context = context
        fetch()
    }

    func fetch() {
        let request: NSFetchRequest<Meal> = Meal.fetchRequest()
        do {
            meals = try context.fetch(request)
            let start = Calendar.current.startOfDay(for: Date())
            totalCaloriesToday = meals.filter { $0.createdAt >= start }.reduce(0) { $0 + $1.calories }
        } catch {
            meals = []
        }
    }

    func addMeal(name: String, type: String, calories: Double, protein: Double, carbs: Double, fats: Double) {
        let meal = Meal(context: context)
        meal.id = UUID()
        meal.name = name
        meal.type = type
        meal.calories = calories
        meal.protein = protein
        meal.carbs = carbs
        meal.fats = fats
        meal.createdAt = Date()
        dataController.save(context: context)
        fetch()
    }

    func deleteMeal(_ meal: Meal) {
        context.delete(meal)
        dataController.save(context: context)
        fetch()
    }
}

// MARK: - Habits & Water
final class HabitViewModel: ObservableObject {
    @Published var habits: [Habit] = []
    @Published var waterLogs: [WaterLog] = []
    @Published var waterGoal: Double = 2500

    private let context: NSManagedObjectContext
    private let dataController = DataController.shared
    private let notificationManager: NotificationManager

    init(context: NSManagedObjectContext, notificationManager: NotificationManager) {
        self.context = context
        self.notificationManager = notificationManager
        fetch()
    }

    func fetch() {
        do {
            habits = try context.fetch(Habit.fetchRequest())
            waterLogs = try context.fetch(WaterLog.fetchRequest())
        } catch {
            habits = []
            waterLogs = []
        }
    }

    func toggleHabit(_ habit: Habit) {
        habit.completed.toggle()
        dataController.save(context: context)
        fetch()
    }

    func addHabit(name: String, hour: Int, minute: Int) {
        let habit = Habit(context: context)
        habit.id = UUID()
        habit.name = name
        habit.completed = false
        habit.reminderHour = Int16(hour)
        habit.reminderMinute = Int16(minute)
        habit.createdAt = Date()
        dataController.save(context: context)
        notificationManager.scheduleDailyReminder(id: habit.id.uuidString, title: "Recordatorio de hábito", body: name, hour: hour, minute: minute)
        fetch()
    }

    func logWater(amount: Double) {
        let log = WaterLog(context: context)
        log.id = UUID()
        log.amountML = amount
        log.date = Date()
        dataController.save(context: context)
        fetch()
    }

    var todayWater: Double {
        let start = Calendar.current.startOfDay(for: Date())
        return waterLogs.filter { $0.date >= start }.reduce(0) { $0 + $1.amountML }
    }
}

// MARK: - Progress
final class ProgressViewModel: ObservableObject {
    @Published var records: [ProgressRecord] = []
    private let context: NSManagedObjectContext
    private let dataController = DataController.shared

    init(context: NSManagedObjectContext) {
        self.context = context
        fetch()
    }

    func fetch() {
        do {
            records = try context.fetch(ProgressRecord.fetchRequest())
        } catch {
            records = []
        }
    }

    func addRecord(metric: String, value: Double) {
        let record = ProgressRecord(context: context)
        record.id = UUID()
        record.metric = metric
        record.value = value
        record.date = Date()
        dataController.save(context: context)
        fetch()
    }

    func data(for metric: String) -> [ProgressRecord] {
        records.filter { $0.metric == metric }
    }
}

// MARK: - Goals
final class GoalViewModel: ObservableObject {
    @Published var currentGoal: Goal?
    private let context: NSManagedObjectContext
    private let dataController = DataController.shared

    init(context: NSManagedObjectContext) {
        self.context = context
        fetch()
    }

    func fetch() {
        do {
            currentGoal = try context.fetch(Goal.fetchRequest()).first
        } catch {
            currentGoal = nil
        }
    }

    func saveGoal(primary: String, frequency: Int, level: String) {
        let calories = recommendedCalories(goal: primary, level: level)
        let plan = weeklyPlan(goal: primary, level: level)
        let groceries = groceryList(goal: primary)

        let goal = currentGoal ?? {
            let newGoal = Goal(context: context)
            newGoal.id = UUID()
            newGoal.createdAt = Date()
            return newGoal
        }()
        goal.primaryGoal = primary
        goal.gymFrequency = Int16(frequency)
        goal.level = level
        goal.recommendedCalories = calories
        goal.weeklyPlan = plan
        goal.groceryList = groceries
        goal.createdAt = Date()
        dataController.save(context: context)
        fetch()
    }

    private func recommendedCalories(goal: String, level: String) -> Double {
        switch goal {
        case "Perder peso": return level == "Avanzado" ? 1800 : 2000
        case "Ganar músculo": return 2600
        default: return 2200
        }
    }

    private func weeklyPlan(goal: String, level: String) -> String {
        switch goal {
        case "Perder peso":
            return "Cardio + Fuerza ligera, \(level)"
        case "Ganar músculo":
            return "Rutinas divididas + proteína alta"
        default:
            return "Full body + movilidad"
        }
    }

    private func groceryList(goal: String) -> String {
        switch goal {
        case "Ganar músculo": return "Pollo, salmón, quinoa, avena, frutos secos"
        case "Perder peso": return "Verduras verdes, pollo magro, batatas, frutos rojos"
        default: return "Alimentos integrales, frutas, semillas"
        }
    }
}

// MARK: - Profile
final class ProfileViewModel: ObservableObject {
    @Published var profile: UserProfile?
    private let context: NSManagedObjectContext
    private let dataController = DataController.shared

    init(context: NSManagedObjectContext) {
        self.context = context
        fetch()
    }

    func fetch() {
        do {
            profile = try context.fetch(UserProfile.fetchRequest()).first
        } catch {
            profile = nil
        }
    }

    func save(name: String, age: Int, height: Double, weight: Double, targetWeight: Double, experience: String, weeklyActivity: Int, syncHealth: Bool, darkMode: Bool) {
        let profile = self.profile ?? UserProfile(context: context)
        if self.profile == nil { profile.id = UUID() }
        profile.name = name
        profile.age = Int16(age)
        profile.height = height
        profile.weight = weight
        profile.targetWeight = targetWeight
        profile.experience = experience
        profile.weeklyActivity = Int16(weeklyActivity)
        profile.syncHealthKit = syncHealth
        profile.prefersDarkMode = darkMode
        profile.createdAt = Date()
        dataController.save(context: context)
        fetch()
    }

    func exportBackup() -> Data? {
        guard let profile else { return nil }
        let dict: [String: Any] = [
            "name": profile.name,
            "age": profile.age,
            "height": profile.height,
            "weight": profile.weight,
            "targetWeight": profile.targetWeight,
            "experience": profile.experience,
            "weeklyActivity": profile.weeklyActivity
        ]
        return try? JSONSerialization.data(withJSONObject: dict, options: .prettyPrinted)
    }
}
