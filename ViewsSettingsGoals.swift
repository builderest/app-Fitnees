import SwiftUI
import CoreData

// MARK: - Goals screen where user defines plan personal
struct GoalsView: View {
    @ObservedObject var viewModel: GoalViewModel
    @Environment(\.dismiss) private var dismiss

    @State private var selectedGoal = "Perder peso"
    @State private var frequency = 3
    @State private var level = "Principiante"

    let goals = ["Perder peso", "Ganar músculo", "Mantener salud"]
    let levels = ["Principiante", "Intermedio", "Avanzado"]

    var body: some View {
        NavigationStack {
            Form {
                Section("Meta principal") {
                    Picker("Objetivo", selection: $selectedGoal) {
                        ForEach(goals, id: \.self, content: Text.init)
                    }
                }

                Section("Frecuencia y nivel") {
                    Stepper("Días de gimnasio: \(frequency)", value: $frequency, in: 1...7)
                    Picker("Nivel", selection: $level) {
                        ForEach(levels, id: \.self, content: Text.init)
                    }
                }

                if let goal = viewModel.currentGoal {
                    planSection(goal: goal)
                }
            }
            .navigationTitle("Plan personal")
            .toolbar {
                ToolbarItem(placement: .cancellationAction) {
                    Button("Cerrar", action: { dismiss() })
                }
                ToolbarItem(placement: .confirmationAction) {
                    Button("Guardar") {
                        viewModel.saveGoal(primary: selectedGoal, frequency: frequency, level: level)
                    }
                }
            }
            .onAppear {
                if let goal = viewModel.currentGoal {
                    selectedGoal = goal.primaryGoal
                    frequency = Int(goal.gymFrequency)
                    level = goal.level
                }
            }
        }
    }

    private func planSection(goal: Goal) -> some View {
        Section("Plan sugerido") {
            Text("Calorías recomendadas: \(Int(goal.recommendedCalories)) kcal")
            Text("Plan semanal: \(goal.weeklyPlan)")
            Text("Lista de compras saludable")
            Text(goal.groceryList)
        }
    }
}

// MARK: - Perfil y configuración
struct ProfileSettingsView: View {
    @ObservedObject var viewModel: ProfileViewModel
    @Environment(\.dismiss) private var dismiss
    @State private var name = ""
    @State private var age = "25"
    @State private var height = "170"
    @State private var weight = "70"
    @State private var targetWeight = "68"
    @State private var experience = "Principiante"
    @State private var weeklyActivity = 3
    @State private var syncHealth = false
    @State private var prefersDark = false
    @State private var showExport = false

    let experiences = ["Principiante", "Intermedio", "Avanzado"]

    var body: some View {
        NavigationStack {
            Form {
                Section("Información personal") {
                    TextField("Nombre", text: $name)
                    TextField("Edad", text: $age).keyboardType(.numberPad)
                    TextField("Altura (cm)", text: $height).keyboardType(.decimalPad)
                    TextField("Peso actual", text: $weight).keyboardType(.decimalPad)
                    TextField("Peso meta", text: $targetWeight).keyboardType(.decimalPad)
                }

                Section("Experiencia") {
                    Picker("Nivel", selection: $experience) {
                        ForEach(experiences, id: \.self, content: Text.init)
                    }
                    Stepper("Actividad semanal: \(weeklyActivity) días", value: $weeklyActivity, in: 1...7)
                    Toggle("Sincronizar con HealthKit", isOn: $syncHealth)
                    Toggle("Tema oscuro", isOn: $prefersDark)
                }

                if showExport, let data = viewModel.exportBackup(), let json = String(data: data, encoding: .utf8) {
                    Section("Backup JSON") {
                        ScrollView { Text(json).font(.footnote).textSelection(.enabled) }
                    }
                }
            }
            .navigationTitle("Perfil")
            .toolbar {
                ToolbarItem(placement: .cancellationAction) { Button("Cerrar", action: { dismiss() }) }
                ToolbarItem(placement: .confirmationAction) {
                    Button("Guardar") {
                        viewModel.save(name: name,
                                       age: Int(age) ?? 0,
                                       height: Double(height) ?? 0,
                                       weight: Double(weight) ?? 0,
                                       targetWeight: Double(targetWeight) ?? 0,
                                       experience: experience,
                                       weeklyActivity: weeklyActivity,
                                       syncHealth: syncHealth,
                                       darkMode: prefersDark)
                        showExport = true
                    }
                }
            }
            .onAppear {
                if let profile = viewModel.profile {
                    name = profile.name
                    age = "\(profile.age)"
                    height = String(format: "%.0f", profile.height)
                    weight = String(format: "%.1f", profile.weight)
                    targetWeight = String(format: "%.1f", profile.targetWeight)
                    experience = profile.experience
                    weeklyActivity = Int(profile.weeklyActivity)
                    syncHealth = profile.syncHealthKit
                    prefersDark = profile.prefersDarkMode
                }
            }
        }
    }
}
