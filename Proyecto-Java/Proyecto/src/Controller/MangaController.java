package Controller;

import dao.MangaDAO;
import Model.Manga;
import java.util.List;
import java.util.Scanner;
import java.sql.SQLException;

public class MangaController {
    private final MangaDAO mangaDAO = new MangaDAO();
    private final Scanner scanner = new Scanner(System.in);

    public void start() {
        while (true) {
            System.out.println("\n1. Add Manga");
            System.out.println("2. View All Mangas");
            System.out.println("3. Update Manga");
            System.out.println("4. Delete Manga");
            System.out.println("5. Export Mangas to TXT");
            System.out.println("6. Import Mangas from TXT");
            System.out.println("7. Exit");
            System.out.print("Choose an option: ");
            int choice = scanner.nextInt();
            scanner.nextLine(); // Consume newline

            try {
                switch (choice) {
                    case 1 -> addManga();
                    case 2 -> viewAllMangas();
                    case 3 -> updateManga();
                    case 4 -> deleteManga();
                    case 5 -> exportMangas();
                    case 6 -> importMangas();
                    case 7 -> {
                        System.out.println("Exiting application.");
                        return;
                    }
                    default -> System.out.println("Invalid option. Try again.");
                }
            } catch (SQLException e) {
                System.err.println("Database error: " + e.getMessage());
            }
        }
    }

    private void addManga() throws SQLException {
        System.out.print("Enter title: ");
        String title = scanner.nextLine();
        System.out.print("Enter author: ");
        String author = scanner.nextLine();
        System.out.print("Enter price: ");
        double price = scanner.nextDouble();
        System.out.print("Enter stock: ");
        int stock = scanner.nextInt();
        scanner.nextLine(); // Consume newline

        Manga manga = new Manga(0, title, author, price, stock);
        mangaDAO.create(manga);
        System.out.println("Manga added successfully.");
    }

    private void viewAllMangas() throws SQLException {
        List<Manga> mangas = mangaDAO.readAll();
        if (mangas.isEmpty()) {
            System.out.println("No mangas found.");
        } else {
            mangas.forEach(System.out::println);
        }
    }

    private void updateManga() throws SQLException {
        System.out.print("Enter Manga ID to update: ");
        int id = scanner.nextInt();
        scanner.nextLine(); // Consume newline
        System.out.print("Enter new title: ");
        String title = scanner.nextLine();
        System.out.print("Enter new author: ");
        String author = scanner.nextLine();
        System.out.print("Enter new price: ");
        double price = scanner.nextDouble();
        System.out.print("Enter new stock: ");
        int stock = scanner.nextInt();
        scanner.nextLine(); // Consume newline

        Manga manga = new Manga(id, title, author, price, stock);
        mangaDAO.update(manga);
        System.out.println("Manga updated successfully.");
    }

    private void deleteManga() throws SQLException {
        System.out.print("Enter Manga ID to delete: ");
        int id = scanner.nextInt();
        scanner.nextLine(); // Consume newline
        mangaDAO.delete(id);
    }

    private void exportMangas() {
        System.out.print("Enter filename to export (e.g., mangas.txt): ");
        String filename = scanner.nextLine();
        mangaDAO.exportToTextFile(filename);
    }

    private void importMangas() {
        System.out.print("Enter filename to import from (e.g., mangas.txt): ");
        String filename = scanner.nextLine();
        mangaDAO.importFromTextFile(filename);
    }
}
