package Modelo;

public class Proveedor {
    private int id;
    private String nombre;
    private String empresa;
    private String telefono;
    private String ciudad;

    // Constructor
    public Proveedor(String nombre, String empresa, String telefono, String ciudad) {
        this.nombre = nombre;
        this.empresa = empresa;
        this.telefono = telefono;
        this.ciudad = ciudad;
    }

    // Getters y Setters
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getEmpresa() {
        return empresa;
    }

    public void setEmpresa(String empresa) {
        this.empresa = empresa;
    }

    public String getTelefono() {
        return telefono;
    }

    public void setTelefono(String telefono) {
        this.telefono = telefono;
    }

    public String getCiudad() {
        return ciudad;
    }

    public void setCiudad(String ciudad) {
        this.ciudad = ciudad;
    }
}
