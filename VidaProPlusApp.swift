import SwiftUI
import CoreData
import UserNotifications

// MARK: - Data Controller handling Core Data stack
final class DataController {
    static let shared = DataController()
    let container: NSPersistentContainer

    private init(inMemory: Bool = false) {
        container = NSPersistentContainer(name: "VidaProPlusModel")
        if inMemory {
            container.persistentStoreDescriptions.first?.url = URL(fileURLWithPath: "/dev/null")
        }
        container.loadPersistentStores { _, error in
            if let error = error {
                fatalError("Failed to load persistent stores: \(error)")
            }
        }
        container.viewContext.mergePolicy = NSMergeByPropertyObjectTrumpMergePolicy
        container.viewContext.automaticallyMergesChangesFromParent = true
    }

    func save(context: NSManagedObjectContext) {
        guard context.hasChanges else { return }
        do {
            try context.save()
        } catch {
            print("Core Data save error: \(error)")
        }
    }
}

// MARK: - Notification Manager for local reminders
final class NotificationManager: NSObject, ObservableObject, UNUserNotificationCenterDelegate {
    @Published var authorizationGranted = false

    override init() {
        super.init()
        UNUserNotificationCenter.current().delegate = self
    }

    func requestAuthorization() {
        UNUserNotificationCenter.current().requestAuthorization(options: [.alert, .badge, .sound]) { granted, _ in
            DispatchQueue.main.async {
                self.authorizationGranted = granted
            }
        }
    }

    func scheduleNotification(identifier: String, title: String, body: String, trigger: UNNotificationTrigger) {
        let content = UNMutableNotificationContent()
        content.title = title
        content.body = body
        content.sound = .default
        let request = UNNotificationRequest(identifier: identifier, content: content, trigger: trigger)
        UNUserNotificationCenter.current().add(request) { error in
            if let error = error {
                print("Notification scheduling error: \(error)")
            }
        }
    }

    func scheduleDailyReminder(id: String, title: String, body: String, hour: Int, minute: Int) {
        var date = DateComponents()
        date.hour = hour
        date.minute = minute
        let trigger = UNCalendarNotificationTrigger(dateMatching: date, repeats: true)
        scheduleNotification(identifier: id, title: title, body: body, trigger: trigger)
    }

    func scheduleRepeatingReminder(id: String, title: String, body: String, interval: TimeInterval) {
        let trigger = UNTimeIntervalNotificationTrigger(timeInterval: interval, repeats: true)
        scheduleNotification(identifier: id, title: title, body: body, trigger: trigger)
    }

    func cancelNotification(id: String) {
        UNUserNotificationCenter.current().removePendingNotificationRequests(withIdentifiers: [id])
    }

    func userNotificationCenter(_ center: UNUserNotificationCenter, willPresent notification: UNNotification, withCompletionHandler completionHandler: @escaping (UNNotificationPresentationOptions) -> Void) {
        completionHandler([.banner, .list, .sound])
    }
}

// MARK: - Theme helper
struct VidaTheme {
    static let gradient = LinearGradient(colors: [.green, .blue, .purple], startPoint: .topLeading, endPoint: .bottomTrailing)
    static let cardBackground = Color(uiColor: .secondarySystemBackground)
}

// MARK: - Root App Entry
@main
struct VidaProPlusApp: App {
    @UIApplicationDelegateAdaptor(AppDelegate.self) var appDelegate
    let dataController = DataController.shared
    @StateObject var notificationManager = NotificationManager()

    var body: some Scene {
        WindowGroup {
            RootTabView()
                .environment(\.managedObjectContext, dataController.container.viewContext)
                .environmentObject(notificationManager)
        }
    }
}

// MARK: - App Delegate for notification set up
final class AppDelegate: NSObject, UIApplicationDelegate {
    func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]? = nil) -> Bool {
        UNUserNotificationCenter.current().requestAuthorization(options: [.alert, .sound, .badge]) { _, _ in }
        return true
    }
}
