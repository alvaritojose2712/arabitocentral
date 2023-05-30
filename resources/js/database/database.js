import {useState} from 'react';
import axios from 'axios';

// import '../css/loading.css';




const host = ""
// const host = "http://localhost/arabitoapp"

const db = {
  getSucursales: data=>axios.get(host+"getSucursales",{params:data}),
  getFallas: data=>axios.get(host+"getFallas",{params:data}),

  getGastos: data=>axios.get(host+"getGastos",{params:data}),
  getVentas: data=>axios.get(host+"getVentas",{params:data}),
  today: data=>axios.get(host+"today",{params:data}),

  setCarrito: data=>axios.post(host+"setCarrito",data),

  getPedidosList: data=>axios.post(host+"getPedidosList",data),

  getPedidos: data=>axios.post(host+"getPedidos",data),
  delPedido: data=>axios.post(host+"delPedido",data),
  getPedido: data=>axios.post(host+"getPedido",data),

  setCtCarrito: data=>axios.post(host+"setCtCarrito",data),
  setDelCarrito: data=>axios.post(host+"setDelCarrito",data),  
  sendPedidoSucursal: data=>axios.post(host+"sendPedidoSucursal",data),
  
  openReporteFalla: data=>axios.post(host+"openReporteFalla",data),
  getPagoProveedor: data=>axios.post(host+"getPagoProveedor",data),
  setPagoProveedor: data=>axios.post(host+"setPagoProveedor",data),
  delItemFact: data=>axios.post(host+"delItemFact",data),
  delFalla: data=>axios.post(host+"delFalla",data),
  setFactura: data=>axios.post(host+"setFactura",data),
  delFactura: data=>axios.post(host+"delFactura",data),
  saveMontoFactura: data=>axios.post(host+"saveMontoFactura",data),
  delProveedor: data=>axios.post(host+"delProveedor",data),
  delProducto: data=>axios.post(host+"delProducto",data),
  getFacturas: data=>axios.post(host+"getFacturas",data),
  guardarNuevoProducto: data=>axios.post(host+"guardarNuevoProducto",data),
  setProveedor: data=>axios.post(host+"setProveedor",data),
  openReporteInventario: data=>axios.post(host+"openReporteInventario",data),
  guardarNuevoProductoLote: data=>axios.post(host+"guardarNuevoProductoLote",data),
  getEstaInventario: data=>axios.post(host+"getEstaInventario",data),
  getinventario: data=>axios.post(host+"getinventario",data),
  getProveedores: data=>axios.post(host+"getProveedores",data),
  getCategorias: data=>axios.get(host+"getCategorias",{params:data}),
  getDepositos: data=>axios.post(host+"getDepositos",data),
  getFallas: data=>axios.post(host+"getFallas",data),
  setFalla: data=>axios.post(host+"setFalla",data),
  getSucursal: data=>axios.post(host+"getSucursal",data),
  openVerFactura: data=>axios.post(host+"openVerFactura",data),
  
  getsucursalListData: data=>axios.post(host+"getsucursalListData",data),
  getsucursalDetallesData: data=>axios.post(host+"getsucursalDetallesData",data),

  

  

}

export default db