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


  today: data=>axios.get(host+"today",{params:data}),

  getinventario: data=>axios.post(host+"getinventario",data),
  guardarNuevoProducto: data=>axios.post(host+"guardarNuevoProducto",data),
  delProducto: data=>axios.post(host+"delProducto",data),
  setProveedor: data=>axios.post(host+"setProveedor",data),
  delProveedor: data=>axios.post(host+"delProveedor",data),
  getProveedores: data=>axios.post(host+"getProveedores",data),
  getMarcas: data=>axios.post(host+"getMarcas",data),
  getDepositos: data=>axios.post(host+"getDepositos",data),
  setFactura: data=>axios.post(host+"setFactura",data),
  getFacturas: data=>axios.post(host+"getFacturas",data),
  delFactura: data=>axios.post(host+"delFactura",data),
  delItemFact: data=>axios.post(host+"delItemFact",data),

  setCarrito: data=>axios.post(host+"setCarrito",data),

  getPedidosList: data=>axios.post(host+"getPedidosList",data),

  getPedidos: data=>axios.post(host+"getPedidos",data),
  delPedido: data=>axios.post(host+"delPedido",data),
  getPedido: data=>axios.post(host+"getPedido",data),

  setCtCarrito: data=>axios.post(host+"setCtCarrito",data),
  setDelCarrito: data=>axios.post(host+"setDelCarrito",data),  
  sendPedidoSucursal: data=>axios.post(host+"sendPedidoSucursal",data),  
  

}

export default db