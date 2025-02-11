package dao;

import Model.Manga;
import Model.DatabaseManager;
import java.sql.*;
import java.io.*;
import java.util.ArrayList;
import java.util.List;

public class MangaDAO {
    public void create(Manga manga) {
        String sql = "INSERT INTO mangas (title, author, price, stock) VALUES (?, ?, ?, ?)";
        try (Connection connection = DatabaseManager.getConnection();
             PreparedStatement statement = connection.prepareStatement(sql)) {

            statement.setString(1, manga.getTitle());
            statement.setString(2, manga.getAuthor());
            statement.setDouble(3, manga.getPrice());
            statement.setInt(4, manga.getStock());
            statement.executeUpdate();
            System.out.println("Manga added successfully.");

        } catch (SQLException e) {
            System.err.println("Error inserting manga: " + e.getMessage());
        }
    }

    public List<Manga> readAll() {
        List<Manga> mangas = new ArrayList<>();
        String sql = "SELECT * FROM mangas";
        try (Connection connection = DatabaseManager.getConnection();
             Statement statement = connection.createStatement();
             ResultSet resultSet = statement.executeQuery(sql)) {

            while (resultSet.next()) {
                mangas.add(new Manga(
                        resultSet.getInt("id"),
                        resultSet.getString("title"),
                        resultSet.getString("author"),
                        resultSet.getDouble("price"),
                        resultSet.getInt("stock")
                ));
            }
        } catch (SQLException e) {
            System.err.println("Error reading mangas: " + e.getMessage());
        }
        return mangas;
    }

    public void update(Manga manga) {
        String sql = "UPDATE mangas SET title = ?, author = ?, price = ?, stock = ? WHERE id = ?";
        try (Connection connection = DatabaseManager.getConnection();
             PreparedStatement statement = connection.prepareStatement(sql)) {

            statement.setString(1, manga.getTitle());
            statement.setString(2, manga.getAuthor());
            statement.setDouble(3, manga.getPrice());
            statement.setInt(4, manga.getStock());
            statement.setInt(5, manga.getId());
            int rowsAffected = statement.executeUpdate();

            if (rowsAffected > 0) {
                System.out.println("Manga updated successfully.");
            } else {
                System.out.println("No manga found with the given ID.");
            }

        } catch (SQLException e) {
            System.err.println("Error updating manga: " + e.getMessage());
        }
    }

    public void delete(int id) {
        String sql = "DELETE FROM mangas WHERE id = ?";
        try (Connection connection = DatabaseManager.getConnection();
             PreparedStatement statement = connection.prepareStatement(sql)) {

            statement.setInt(1, id);
            int rowsAffected = statement.executeUpdate();

            if (rowsAffected > 0) {
                System.out.println("Manga deleted successfully.");
            } else {
                System.out.println("No manga found with the given ID.");
            }

        } catch (SQLException e) {
            System.err.println("Error deleting manga: " + e.getMessage());
        }
    }

    public void exportToTextFile(String filename) {
        List<Manga> mangas = readAll();
        try (BufferedWriter writer = new BufferedWriter(new FileWriter(filename))) {
            for (Manga manga : mangas) {
                writer.write(manga.getId() + "," + manga.getTitle() + "," + manga.getAuthor() + "," + manga.getPrice() + "," + manga.getStock());
                writer.newLine();
            }
            System.out.println("Mangas exported successfully to " + filename);
        } catch (IOException e) {
            System.err.println("Error exporting mangas: " + e.getMessage());
        }
    }

    public void importFromTextFile(String filename) {
        File file = new File(filename);
        if (!file.exists()) {
            System.err.println("Error: The file '" + filename + "' does not exist. Please check the filename and try again.");
            return;
        }

        try (BufferedReader reader = new BufferedReader(new FileReader(file))) {
            String line;
            while ((line = reader.readLine()) != null) {
                String[] data = line.split(",");
                if (data.length == 5) {
                    String title = data[1];
                    String author = data[2];
                    double price = Double.parseDouble(data[3]);
                    int stock = Integer.parseInt(data[4]);

                    create(new Manga(title, author, price, stock));
                }
            }
            System.out.println("Mangas imported successfully from " + filename);
        } catch (IOException | NumberFormatException e) {
            System.err.println("Error importing mangas: " + e.getMessage());
        }
    }
}
