package Model;

public class Manga {
    private int id;
    private String title;
    private String author;
    private double price;
    private int stock;

    // Constructor con todos los atributos
    public Manga(int id, String title, String author, double price, int stock) {
        this.id = id;
        this.title = title;
        this.author = author;
        this.price = price;
        this.stock = stock;
    }

    // Constructor sin ID (para nuevos registros)
    public Manga(String title, String author, double price, int stock) {
        this(0, title, author, price, stock);
    }

    // Getters y setters
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getAuthor() {
        return author;
    }

    public void setAuthor(String author) {
        this.author = author;
    }

    public double getPrice() {
        return price;
    }

    public void setPrice(double price) {
        this.price = price;
    }

    public int getStock() {
        return stock;
    }

    public void setStock(int stock) {
        this.stock = stock;
    }


    @Override
    public String toString() {
        return "Manga: " +
                "id=" + id +
                ", title='" + title + '\'' +
                ", author='" + author + '\'' +
                ", price=" + price +
                ", stock=" + stock ;
    }
}
