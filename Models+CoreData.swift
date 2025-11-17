import Foundation
import CoreData

// MARK: - Routine & Exercise
@objc(Routine)
public class Routine: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var name: String
    @NSManaged public var category: String
    @NSManaged public var createdAt: Date
    @NSManaged public var notify: Bool
    @NSManaged public var exercises: NSSet?
}

@objc(Exercise)
public class Exercise: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var name: String
    @NSManaged public var sets: Int16
    @NSManaged public var reps: Int16
    @NSManaged public var weight: Double
    @NSManaged public var duration: Double
    @NSManaged public var createdAt: Date
    @NSManaged public var routine: Routine?
}

// MARK: - Meal
@objc(Meal)
public class Meal: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var name: String
    @NSManaged public var type: String
    @NSManaged public var calories: Double
    @NSManaged public var protein: Double
    @NSManaged public var carbs: Double
    @NSManaged public var fats: Double
    @NSManaged public var photoData: Data?
    @NSManaged public var createdAt: Date
}

// MARK: - Habit & WaterLog
@objc(Habit)
public class Habit: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var name: String
    @NSManaged public var completed: Bool
    @NSManaged public var reminderHour: Int16
    @NSManaged public var reminderMinute: Int16
    @NSManaged public var createdAt: Date
}

@objc(WaterLog)
public class WaterLog: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var amountML: Double
    @NSManaged public var date: Date
}

// MARK: - Progress Records & Goals
@objc(ProgressRecord)
public class ProgressRecord: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var metric: String
    @NSManaged public var value: Double
    @NSManaged public var date: Date
}

@objc(Goal)
public class Goal: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var primaryGoal: String
    @NSManaged public var gymFrequency: Int16
    @NSManaged public var level: String
    @NSManaged public var recommendedCalories: Double
    @NSManaged public var weeklyPlan: String
    @NSManaged public var groceryList: String
    @NSManaged public var createdAt: Date
}

@objc(UserProfile)
public class UserProfile: NSManagedObject {
    @NSManaged public var id: UUID
    @NSManaged public var name: String
    @NSManaged public var age: Int16
    @NSManaged public var height: Double
    @NSManaged public var weight: Double
    @NSManaged public var targetWeight: Double
    @NSManaged public var experience: String
    @NSManaged public var weeklyActivity: Int16
    @NSManaged public var syncHealthKit: Bool
    @NSManaged public var prefersDarkMode: Bool
    @NSManaged public var createdAt: Date
}

// MARK: - Convenience fetch requests
extension Routine {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<Routine> {
        let request = NSFetchRequest<Routine>(entityName: "Routine")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \Routine.createdAt, ascending: false)]
        return request
    }

    var exerciseArray: [Exercise] {
        (exercises?.allObjects as? [Exercise] ?? []).sorted { $0.createdAt < $1.createdAt }
    }
}

extension Exercise {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<Exercise> {
        let request = NSFetchRequest<Exercise>(entityName: "Exercise")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \Exercise.createdAt, ascending: true)]
        return request
    }
}

extension Meal {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<Meal> {
        let request = NSFetchRequest<Meal>(entityName: "Meal")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \Meal.createdAt, ascending: false)]
        return request
    }
}

extension Habit {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<Habit> {
        let request = NSFetchRequest<Habit>(entityName: "Habit")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \Habit.createdAt, ascending: true)]
        return request
    }
}

extension WaterLog {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<WaterLog> {
        let request = NSFetchRequest<WaterLog>(entityName: "WaterLog")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \WaterLog.date, ascending: false)]
        return request
    }
}

extension ProgressRecord {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<ProgressRecord> {
        let request = NSFetchRequest<ProgressRecord>(entityName: "ProgressRecord")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \ProgressRecord.date, ascending: true)]
        return request
    }
}

extension Goal {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<Goal> {
        let request = NSFetchRequest<Goal>(entityName: "Goal")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \Goal.createdAt, ascending: false)]
        request.fetchLimit = 1
        return request
    }
}

extension UserProfile {
    @nonobjc public class func fetchRequest() -> NSFetchRequest<UserProfile> {
        let request = NSFetchRequest<UserProfile>(entityName: "UserProfile")
        request.sortDescriptors = [NSSortDescriptor(keyPath: \UserProfile.createdAt, ascending: true)]
        request.fetchLimit = 1
        return request
    }
}
