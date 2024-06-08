
package Modelo;

import Modelo.BDConnect;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;
import javax.jws.WebMethod;
import javax.jws.WebParam;
import javax.jws.WebService;




@WebService(serviceName = "ProveedorService")
public class ProveedorService {
    
     @WebMethod(operationName = "hello")
    public String hello(@WebParam(name = "name") String txt) {
        return "Hello papi" + txt + " !";
    }

    @WebMethod(operationName = "insertarProveedor")
    public String insertarProveedor(
            @WebParam(name = "nombre") String nombre,
            @WebParam(name = "empresa") String empresa,
            @WebParam(name = "telefono") String telefono,
            @WebParam(name = "ciudad") String ciudad) {
        
        Connection con = null;
        PreparedStatement ps = null;
        
        try {
            con = BDConnect.serverConnect();
            String sql = "INSERT INTO proveedores (nombre, empresa, telefono, ciudad) VALUES (?, ?, ?, ?)";
            ps = con.prepareStatement(sql);
            ps.setString(1, nombre);
            ps.setString(2, empresa);
            ps.setString(3, telefono);
            ps.setString(4, ciudad);
            ps.executeUpdate();
            return "Proveedor insertado con éxito";
        } catch (Exception e) {
            e.printStackTrace();
            return "Error al insertar el proveedor: " + e.getMessage();
        } finally {
            try {
                if (ps != null) ps.close();
                if (con != null) con.close();
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }
    
    // Método para obtener todos los proveedores
    @WebMethod(operationName = "getAllProveedores")
    public List<Proveedor> getAllProveedores() {
        Connection con = null;
        PreparedStatement ps = null;
        ResultSet rs = null;
        List<Proveedor> proveedores = new ArrayList<>();
        
        try {
            con = BDConnect.serverConnect();
            String sql = "SELECT id, nombre, empresa, telefono, ciudad FROM proveedores";
            ps = con.prepareStatement(sql);
            rs = ps.executeQuery();
            
            while (rs.next()) {
                Proveedor proveedor = new Proveedor(
                    rs.getString("nombre"),
                    rs.getString("empresa"),
                    rs.getString("telefono"),
                    rs.getString("ciudad")
                );
                proveedor.setId(rs.getInt("id"));
                proveedores.add(proveedor);
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
        return proveedores;
    }
    
    // Método para actualizar un proveedor
@WebMethod(operationName = "actualizarProveedor")
public String actualizarProveedor(
    @WebParam(name = "id") int id,
    @WebParam(name = "nombre") String nombre,
    @WebParam(name = "empresa") String empresa,
    @WebParam(name = "telefono") String telefono,
    @WebParam(name = "ciudad") String ciudad) {

    Connection con = null;
    PreparedStatement ps = null;

    try {
        con = BDConnect.serverConnect();
        String sql = "UPDATE proveedores SET nombre=?, empresa=?, telefono=?, ciudad=? WHERE id=?";
        ps = con.prepareStatement(sql);
        ps.setString(1, nombre);
        ps.setString(2, empresa);
        ps.setString(3, telefono);
        ps.setString(4, ciudad);
        ps.setInt(5, id);
        
        int rowsUpdated = ps.executeUpdate();
        if (rowsUpdated > 0) {
            return "Proveedor actualizado exitosamente.";
        } else {
            return "Error al actualizar el proveedor.";
        }
    } catch (Exception e) {
        e.printStackTrace();
        return "Error al conectar con la base de datos: " + e.getMessage();
    } finally {
        try {
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

// Método para eliminar un proveedor por su ID
@WebMethod(operationName = "eliminarProveedor")
public String eliminarProveedor(@WebParam(name = "id") int id) {
    Connection con = null;
    PreparedStatement ps = null;
    String mensaje = "";

    try {
        con = BDConnect.serverConnect();
        String sql = "DELETE FROM proveedores WHERE id = ?";
        ps = con.prepareStatement(sql);
        ps.setInt(1, id);

        int rowsAffected = ps.executeUpdate();
        if (rowsAffected > 0) {
            mensaje = "Proveedor eliminado exitosamente.";
        } else {
            mensaje = "No se encontró ningún proveedor con el ID proporcionado.";
        }
    } catch (Exception e) {
        e.printStackTrace();
        mensaje = "Error al eliminar el proveedor: " + e.getMessage();
    } finally {
        try {
            if (ps != null) ps.close();
            if (con != null) con.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    return mensaje;
}

}
