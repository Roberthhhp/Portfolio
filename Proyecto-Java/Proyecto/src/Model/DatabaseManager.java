package Model;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseManager {
    private static final String URL = "jdbc:postgresql://localhost:5432/MangaStore";
    private static final String USER = "postgres";
    private static final String PASSWORD = "princesa";
    private static Connection connection;

    // Iniciar la conexión solo una vez
    public static void initConnection() {
        try {
            if (connection == null || connection.isClosed()) {
                connection = DriverManager.getConnection(URL, USER, PASSWORD);
                System.out.println("Database connected successfully!");
            }
        } catch (SQLException e) {
            System.err.println("Database connection failed: " + e.getMessage());
        }
    }

    // Obtener la conexión activa
    public static Connection getConnection() {
        try {
            if (connection == null || connection.isClosed()) {
                initConnection(); // Reintenta abrir la conexión si se cerró
            }
        } catch (SQLException e) {
            System.err.println("Error checking database connection: " + e.getMessage());
        }
        return connection;
    }

    // Cerrar la conexión cuando se termine el programa
    public static void closeConnection() {
        try {
            if (connection != null && !connection.isClosed()) {
                connection.close();
                System.out.println("Database connection closed.");
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
    }
}

