// --- Singleton ---
class DatabaseConnection {
    private static DatabaseConnection instance;
    private DatabaseConnection() { System.out.println("[DB] Koneksi Terhubung."); }
    public static DatabaseConnection getInstance() {
        if (instance == null) instance = new DatabaseConnection();
        return instance;
    }
}

// --- BEHAVIORAL (Command & Strategy) ---
interface Command { void execute(); }
class LightCommand implements Command {
    public void execute() { System.out.println("[Hardware] Lampu Meja dinyalakan."); }
}

interface PricingStrategy { double calculate(int hours); }
class PeakHourStrategy implements PricingStrategy {
    public double calculate(int hours) { return hours * 50000; }
}

// --- STRUCTURAL (Adapter, Decorator, Proxy) ---
interface PaymentProcessor { void pay(int amount); }
class MidtransAdapter implements PaymentProcessor {
    public void pay(int amount) { System.out.println("[Payment] Membayar via Midtrans."); }
}

interface Order { double cost(); }
class TableBooking implements Order { public double cost() { return 50000; } }

class StickProDecorator implements Order {
    Order order;
    StickProDecorator(Order o) { this.order = o; }
    public double cost() { return order.cost() + 15000; }
}

class ReservationProxy {
    private boolean isBlacklisted = false;
    public boolean checkAccess() {
        if(isBlacklisted) {
            System.out.println("[Proxy] Akses Ditolak! User di-blacklist.");
            return false;
        }
        System.out.println("[Proxy] User Aman.");
        return true;
    }
}

// --- FACADE ---
class BookingFacade {
    private PricingStrategy pricing = new PeakHourStrategy();
    private PaymentProcessor payment = new MidtransAdapter();
    private LightCommand light = new LightCommand();

    public void confirmBooking() {
        System.out.println("=== Memulai Proses Checkout (Facade) ===");
        DatabaseConnection.getInstance();
        light.execute();
        double total = pricing.calculate(2);
        payment.pay((int)total);
        System.out.println("=== Booking Berhasil! ===");
    }
}

// --- MAIN CLASS ---
public class Master {
    public static void main(String[] args) {
        ReservationProxy proxy = new ReservationProxy();
        
        if(proxy.checkAccess()) {
            BookingFacade system = new BookingFacade();
            system.confirmBooking();
        }
    }
}
