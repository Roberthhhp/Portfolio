package View;

import Model.Manga;

import java.util.List;
import java.util.Scanner;

public class ConsoleView {
    private final Scanner scanner = new Scanner(System.in);

    public int showMenu() {
        System.out.println("\n1. Add Manga");
        System.out.println("2. List Mangas");
        System.out.println("3. Update Manga");
        System.out.println("4. Delete Manga");
        System.out.println("5. Exit");
        System.out.print("Choose an option: ");
        return scanner.nextInt();
    }

    public Manga getMangaDetails() {
        System.out.print("Enter title: ");
        String title = scanner.next();
        System.out.print("Enter author: ");
        String author = scanner.next();
        System.out.print("Enter price: ");
        double price = scanner.nextDouble();
        System.out.print("Enter stock: ");
        int stock = scanner.nextInt();
        return new Manga(0, title, author, price, stock);
    }

    public void displayMangas(List<Manga> mangas) {
        for (Manga manga : mangas) {
            System.out.println(manga);
        }
    }

    public int getIdToUpdate() {
        System.out.print("Enter Manga ID to update: ");
        return scanner.nextInt();
    }

    public int getIdToDelete() {
        System.out.print("Enter Manga ID to delete: ");
        return scanner.nextInt();
    }
}
