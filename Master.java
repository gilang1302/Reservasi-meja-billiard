import java.util.ArrayList;
import java.util.List;

// --- 1. SINGLETON: Koneksi Database ---
class DatabaseConnection {
    private static DatabaseConnection instance;
    private DatabaseConnection() { System.out.println("[DB] Koneksi Terhubung ke CueMaster DB."); }
    public static DatabaseConnection getInstance() {
        if (instance == null) instance = new DatabaseConnection();
        return instance;
    }
}

// --- 2. FACTORY PATTERN: Tipe Member ---
interface Member { String getBenefit(); }
class GoldMember implements Member { public String getBenefit() { return "Diskon 20% & Prioritas Meja"; } }
class BronzeMember implements Member { public String getBenefit() { return "Diskon 5%"; } }

class MemberFactory {
    public static Member getMember(String type) {
        if(type.equalsIgnoreCase("GOLD")) return new GoldMember();
        return new BronzeMember();
    }
}

// --- 3. STATE PATTERN: Status Meja  ---
interface TableState { void handleStatus(); }
class OccupiedState implements TableState {
    public void handleStatus() { System.out.println("[State] Meja sedang digunakan. Reservasi ditutup."); }
}
class AvailableState implements TableState {
    public void handleStatus() { System.out.println("[State] Meja tersedia untuk dipesan."); }
}

// --- 4. OBSERVER PATTERN: Notifikasi ---
interface Observer { void update(String message); }
class CustomerNotification implements Observer {
    public void update(String message) { System.out.println("[WhatsApp] Notifikasi: " + message); }
}

class NotificationService {
    private List<Observer> observers = new ArrayList<>();
    public void addObserver(Observer s) { observers.add(s); }
    public void notifyAll(String msg) { for(Observer s : observers) s.update(msg); }
}

// --- 5. STRATEGY & ADAPTER (Perhitungan & Pembayaran) ---
interface PricingStrategy { double calculate(int hours); }
class PeakHourStrategy implements PricingStrategy { 
    public double calculate(int hours) { return hours * 50000; } 
}

interface PaymentProcessor { void pay(int amount); }
class MidtransAdapter implements PaymentProcessor {
    public void pay(int amount) { System.out.println("[Payment] Berhasil bayar Rp" + amount + " via Midtrans."); }
}

// --- 6. FACADE: Menyederhanakan Checkout ---
class BookingFacade {
    private NotificationService notifier = new NotificationService();
    private PaymentProcessor payment = new MidtransAdapter();
    private PricingStrategy pricing = new PeakHourStrategy();

    public void processBooking(String memberType, int hours) {
        System.out.println("\n=== Memulai Checkout (Facade) ===");
        DatabaseConnection.getInstance();
        
        // Cek Member 
        Member user = MemberFactory.getMember(memberType);
        System.out.println("[Member] Tipe: " + memberType + " | Benefit: " + user.getBenefit());

        // Hitung & Bayar
        double total = pricing.calculate(hours);
        payment.pay((int)total);

        // Kirim Notifikasi
        notifier.addObserver(new CustomerNotification());
        notifier.notifyAll("Booking meja berhasil untuk " + hours + " jam.");
        System.out.println("=== Transaksi Selesai ===\n");
    }
}

// --- MAIN CLASS ---
public class Master {
    public static void main(String[] args) {
        BookingFacade app = new BookingFacade();
        
        // Simulasi User Gold memesan 2 jam
        app.processBooking("GOLD", 2);

        // Simulasi Perubahan State Meja 
        TableState currentState = new OccupiedState();
        currentState.handleStatus();
    }
}
