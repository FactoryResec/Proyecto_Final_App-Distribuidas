package Modelo;

public class Producto {
    private long id;
    private String codigoDeBarras;
    private String nombre;
    private String descripcion;
    private int stock;
    private double precio;
    private int noVentas;
    private long proveedorId;
    private String image;

    // Constructor vacío
    public Producto() {
    }

    // Constructor con parámetros
    public Producto(long id, String codigoDeBarras, String nombre, String descripcion, int stock, double precio, int noVentas, long proveedorId, String image) {
        this.id = id;
        this.codigoDeBarras = codigoDeBarras;
        this.nombre = nombre;
        this.descripcion = descripcion;
        this.stock = stock;
        this.precio = precio;
        this.noVentas = noVentas;
        this.proveedorId = proveedorId;
        this.image = image;
    }

    // Getters y Setters
    public long getId() {
        return id;
    }

    public void setId(long id) {
        this.id = id;
    }

    public String getCodigoDeBarras() {
        return codigoDeBarras;
    }

    public void setCodigoDeBarras(String codigoDeBarras) {
        this.codigoDeBarras = codigoDeBarras;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public int getStock() {
        return stock;
    }

    public void setStock(int stock) {
        this.stock = stock;
    }

    public double getPrecio() {
        return precio;
    }

    public void setPrecio(double precio) {
        this.precio = precio;
    }

    public int getNoVentas() {
        return noVentas;
    }

    public void setNoVentas(int noVentas) {
        this.noVentas = noVentas;
    }

    public long getProveedorId() {
        return proveedorId;
    }

    public void setProveedorId(long proveedorId) {
        this.proveedorId = proveedorId;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }
}
