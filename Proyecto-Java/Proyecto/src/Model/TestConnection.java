package Model;

import Model.DatabaseManager;

public class TestConnection {
    public static void main(String[] args) {
        // Inicia la conexión
        DatabaseManager.initConnection();

        // Verifica si la conexión es válida
        if (DatabaseManager.getConnection() != null) {
            System.out.println("Connected to the database!");
        }

        // Cierra la conexión
        DatabaseManager.closeConnection();
    }
}
