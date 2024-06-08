/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Modelo;

import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;
import javax.jws.WebService;
import javax.jws.WebMethod;
import javax.jws.WebParam;

/**
 *
 * @author cesar
 */
@WebService(serviceName = "ProductoService")
public class ProductoService {

    /**
     * This is a sample web service operation
     */
    
    
    @WebMethod(operationName = "insertarProducto")
public String insertarProducto(
        @WebParam(name = "codigoDeBarras") String codigoDeBarras,
        @WebParam(name = "nombre") String nombre,
        @WebParam(name = "descripcion") String descripcion,
        @WebParam(name = "stock") int stock,
        @WebParam(name = "precio") double precio,
        @WebParam(name = "proveedorId") int proveedorId,
        @WebParam(name = "image") String image) {

    Connection con = null;
    PreparedStatement ps = null;

    try {
        con = BDConnect.serverConnect();
        String sql = "INSERT INTO producto (codigo_de_barras, nombre, descripcion, stock, precio, proveedor_id, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        ps = con.prepareStatement(sql);
        ps.setString(1, codigoDeBarras);
        ps.setString(2, nombre);
        ps.setString(3, descripcion);
        ps.setInt(4, stock);
        ps.setDouble(5, precio);
        ps.setInt(6, proveedorId);
        ps.setString(7, image);
        
        ps.executeUpdate();
        return "Producto insertado con éxito";
    } catch (Exception e) {
        e.printStackTrace();
        return "Error al insertar el producto: " + e.getMessage();
    } finally {
        try {
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

@WebMethod(operationName = "mostrarProductos")
public List<Producto> mostrarProductos() {
    Connection con = null;
    PreparedStatement ps = null;
    ResultSet rs = null;
    List<Producto> productos = new ArrayList<>();

    try {
        con = BDConnect.serverConnect();
        String sql = "SELECT * FROM producto";
        ps = con.prepareStatement(sql);
        rs = ps.executeQuery();

        while (rs.next()) {
            Producto producto = new Producto();
            producto.setId(rs.getLong("id"));
            producto.setCodigoDeBarras(rs.getString("codigo_de_barras"));
            producto.setNombre(rs.getString("nombre"));
            producto.setDescripcion(rs.getString("descripcion"));
            producto.setStock(rs.getInt("stock"));
            producto.setPrecio(rs.getDouble("precio"));
            producto.setProveedorId(rs.getInt("proveedor_id"));
            producto.setImage(rs.getString("image"));
            productos.add(producto);
        }
    } catch (Exception e) {
        e.printStackTrace();
    } finally {
        try {
            if (rs != null) rs.close();
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    return productos;
}


@WebMethod(operationName = "modificarProducto")
public String modificarProducto(
    @WebParam(name = "id") Long id,
    @WebParam(name = "codigoDeBarras") String codigoDeBarras,
    @WebParam(name = "nombre") String nombre,
    @WebParam(name = "descripcion") String descripcion,
    @WebParam(name = "stock") int stock,
    @WebParam(name = "precio") double precio,
    @WebParam(name = "proveedorId") int proveedorId,
    @WebParam(name = "image") String image
) {
    Connection con = null;
    PreparedStatement ps = null;
    try {
        con = BDConnect.serverConnect();
        String sql = "UPDATE producto SET codigo_de_barras=?, nombre=?, descripcion=?, stock=?, precio=?, proveedor_id=?, image=? WHERE id=?";
        ps = con.prepareStatement(sql);
        ps.setString(1, codigoDeBarras);
        ps.setString(2, nombre);
        ps.setString(3, descripcion);
        ps.setInt(4, stock);
        ps.setDouble(5, precio);
        ps.setInt(6, proveedorId);
        ps.setString(7, image);
        ps.setLong(8, id);
        int affectedRows = ps.executeUpdate();
        if (affectedRows > 0) {
            return "Producto modificado con éxito";
        } else {
            return "No se pudo modificar el producto";
        }
    } catch (Exception e) {
        e.printStackTrace();
        return "Error al modificar el producto: " + e.getMessage();
    } finally {
        try {
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

@WebMethod(operationName = "eliminarProducto")
public String eliminarProducto(@WebParam(name = "id") long id) {
    Connection con = null;
    PreparedStatement ps = null;

    try {
        con = BDConnect.serverConnect();
        String sql = "DELETE FROM producto WHERE id = ?";
        ps = con.prepareStatement(sql);
        ps.setLong(1, id);

        int rowsAffected = ps.executeUpdate();
        if (rowsAffected > 0) {
            return "Producto eliminado con éxito";
        } else {
            return "No se encontró el producto con el ID proporcionado";
        }
    } catch (Exception e) {
        e.printStackTrace();
        return "Error al eliminar el producto: " + e.getMessage();
    } finally {
        try {
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

@WebMethod(operationName = "obtenerProducto")
public Producto obtenerProducto(@WebParam(name = "id") Long id) {
    Connection con = null;
    PreparedStatement ps = null;
    ResultSet rs = null;
    Producto producto = null;

    try {
        con = BDConnect.serverConnect();
        String sql = "SELECT * FROM producto WHERE id = ?";
        ps = con.prepareStatement(sql);
        ps.setLong(1, id);
        rs = ps.executeQuery();

        if (rs.next()) {
            producto = new Producto();
            producto.setId(rs.getLong("id"));
            producto.setCodigoDeBarras(rs.getString("codigo_de_barras"));
            producto.setNombre(rs.getString("nombre"));
            producto.setDescripcion(rs.getString("descripcion"));
            producto.setStock(rs.getInt("stock"));
            producto.setPrecio(rs.getDouble("precio"));
            producto.setProveedorId(rs.getInt("proveedor_id"));
            producto.setImage(rs.getString("image"));
            producto.setNoVentas(rs.getInt("noVentas"));
        }
    } catch (Exception e) {
        e.printStackTrace();
    } finally {
        try {
            if (rs != null) rs.close();
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    return producto;
}
    
@WebMethod(operationName = "modificarProductoStockYVentas")
public String modificarProductoStockYVentas(
    @WebParam(name = "id") Long id,
    @WebParam(name = "stock") int stock,
    @WebParam(name = "noVentas") int noVentas
) {
    Connection con = null;
    PreparedStatement ps = null;

    try {
        con = BDConnect.serverConnect();
        String sql = "UPDATE producto SET stock = ?, noVentas = ? WHERE id = ?";
        ps = con.prepareStatement(sql);
        ps.setInt(1, stock);
        ps.setInt(2, noVentas);
        ps.setLong(3, id);

        int rows = ps.executeUpdate();
        return rows > 0 ? "Producto modificado con éxito" : "Error al modificar el producto";
    } catch (Exception e) {
        e.printStackTrace();
        return "Error al modificar el producto: " + e.getMessage();
    } finally {
        try {
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

@WebMethod(operationName = "obtenerProductosMasVendidos")
public List<Producto> obtenerProductosMasVendidos() {
    Connection con = null;
    PreparedStatement ps = null;
    ResultSet rs = null;
    List<Producto> productos = new ArrayList<>();

    try {
        con = BDConnect.serverConnect();
        String sql = "SELECT * FROM producto ORDER BY noVentas DESC LIMIT 10";
        ps = con.prepareStatement(sql);
        rs = ps.executeQuery();

        while (rs.next()) {
            Producto producto = new Producto();
            producto.setId(rs.getLong("id"));
            producto.setCodigoDeBarras(rs.getString("codigo_de_barras"));
            producto.setNombre(rs.getString("nombre"));
            producto.setDescripcion(rs.getString("descripcion"));
            producto.setStock(rs.getInt("stock"));
            producto.setPrecio(rs.getDouble("precio"));
            producto.setProveedorId(rs.getInt("proveedor_id"));
            producto.setNoVentas(rs.getInt("noVentas"));
            producto.setImage(rs.getString("image"));
            productos.add(producto);
        }
    } catch (Exception e) {
        e.printStackTrace();
    } finally {
        try {
            if (rs != null) rs.close();
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    return productos;
}

@WebMethod(operationName = "mostrarProductosBajoStock")
public List<Producto> mostrarProductosBajoStock(@WebParam(name = "stockMinimo") int stockMinimo) {
    Connection con = null;
    PreparedStatement ps = null;
    ResultSet rs = null;
    List<Producto> productos = new ArrayList<>();

    try {
        con = BDConnect.serverConnect();
        String sql = "SELECT * FROM producto WHERE stock < ?";
        ps = con.prepareStatement(sql);
        ps.setInt(1, stockMinimo); // Establecer el valor del parámetro de stock mínimo
        rs = ps.executeQuery();

        while (rs.next()) {
            Producto producto = new Producto();
            producto.setId(rs.getLong("id"));
            producto.setCodigoDeBarras(rs.getString("codigo_de_barras"));
            producto.setNombre(rs.getString("nombre"));
            producto.setDescripcion(rs.getString("descripcion"));
            producto.setStock(rs.getInt("stock"));
            producto.setPrecio(rs.getDouble("precio"));
            producto.setProveedorId(rs.getInt("proveedor_id"));
            producto.setImage(rs.getString("image"));
            productos.add(producto);
        }
    } catch (Exception e) {
        e.printStackTrace();
    } finally {
        try {
            if (rs != null) rs.close();
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    return productos;
}


}

