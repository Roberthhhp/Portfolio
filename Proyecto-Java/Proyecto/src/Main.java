import Controller.MangaController;
import Model.DatabaseManager;

public class Main {
    public static void main(String[] args) {
        // Inicializar la conexión con la base de datos
        DatabaseManager.initConnection();

        // Verificar si la conexión fue exitosa antes de continuar
        if (DatabaseManager.getConnection() == null) {
            System.err.println("Failed to connect to the database. Exiting program.");
            return;
        }

        // Crear el controlador y ejecutar la aplicación
        MangaController controller = new MangaController();
        controller.start();

        // Cerrar la conexión al salir de la aplicación
        DatabaseManager.closeConnection();
    }
}
