import {useState} from 'react';
import axios from 'axios';

// import '../css/loading.css';




const host = ""
// const host = "http://localhost/arabitoapp"

const db = {
  
  logout: data=>axios.get(host+"logout",data),
  aprobarMovCajaFuerte: data=>axios.post(host+"aprobarMovCajaFuerte",data),
  getAprobacionFlujoCaja: data=>axios.post(host+"getAprobacionFlujoCaja",data),
  
  getAprobacionPedidoAnulacion: data=>axios.post(host+"getAprobacionPedidoAnulacion",data),
  setAprobacionPedidoAnulacion: data=>axios.post(host+"setAprobacionPedidoAnulacion",data),
  
  sendReporteDiario: ({ type,fecha }) => window.open(host + "sendReporteDiario?type=" + type + "&fecha=" + fecha,"targed=blank"),

  
  delTareaPendiente: data=>axios.post(host+"delTareaPendiente",data),
  aprobarPermisoModDici: data=>axios.post(host+"aprobarPermisoModDici",data),
  sendVinculoCentralToMaestro: data=>axios.post(host+"sendVinculoCentralToMaestro",data),
  sendVinculoCentralToSucursal: data=>axios.post(host+"sendVinculoCentralToSucursal",data),
  getGarantias: data=>axios.post(host+"getGarantias",data),
  aprobarCreditoFun: data=>axios.post(host+"aprobarCreditoFun",data),
  aprobarTransferenciaFun: data=>axios.post(host+"aprobarTransferenciaFun",data),
  sendComprasFats: data=>axios.post(host+"sendComprasFats",data),
  
  getAlquileres: data=>axios.post(host+"getAlquileres",data),
  setNewAlquiler: data=>axios.post(host+"setNewAlquiler",data),
  delAlquiler: data=>axios.post(host+"delAlquiler",data),
  
  getDatinputSelectVinculacion: data=>axios.post(host+"getDatinputSelectVinculacion",data),
  saveCuatroNombres: data=>axios.post(host+"saveCuatroNombres",data),
  addnewNombre: data=>axios.post(host+"addnewNombre",data),
  
  selectCuentaPorPagarProveedorDetalles: data=>axios.post(host+"selectCuentaPorPagarProveedorDetalles",data),

  selectCuentaPorPagarProveedorDetallesREPORTE: data => window.open(host + "selectCuentaPorPagarProveedorDetalles?"+(new URLSearchParams(data).toString()), "targed=blank"),

  changeLiquidacionPagoElec: data=>axios.post(host+"changeLiquidacionPagoElec",data),
  sendPagoCuentaPorPagar: data=>axios.post(host+"sendPagoCuentaPorPagar",data),
  saveNewFact: data=>axios.post(host+"saveNewFact",data),
  getCatCajas: data=>axios.get(host+"getCatCajas",{params:data}),
  
  getMetodosPago: data=>axios.get(host+"getMetodosPago",{params:data}),
  getBancosData: data=>axios.get(host+"getBancosData",{params:data}),
  getMovBancos: data=>axios.post(host+"getMovBancos",data),
  saveNewmovnoreportado: data=>axios.post(host+"saveNewmovnoreportado",data),
  
  
  sendMovimientoBanco: data=>axios.post(host+"sendMovimientoBanco",data),
  
  sendDescuentoGeneralFats: data=>axios.post(host+"sendDescuentoGeneralFats",data),
  liquidarMov: data=>axios.post(host+"liquidarMov",data),
  reportarMov: data=>axios.post(host+"reportarMov",data),
  setConciliarMovCajaMatriz: data=>axios.post(host+"setConciliarMovCajaMatriz",data),
  
  
  autoliquidarTransferencia: data=>axios.post(host+"autoliquidarTransferencia",data),
  getBalanceGeneral: data=>axios.post(host+"getBalanceGeneral",data),
  getCuadreGeneral: data=>axios.get(host+"getCuadreGeneral",{params:data}),
  
  
  buscarNombres: data=>axios.post(host+"buscarNombres",data),
  modNombres: data=>axios.post(host+"modNombres",data),
  newNombres: data=>axios.post(host+"newNombres",data),
  getAuditoriaEfec: data=>axios.post(host+"getAuditoriaEfec",data),
  
  

  changeBank: data=>axios.post(host+"changeBank",data),
  changeSucursal: data=>axios.post(host+"changeSucursal",data),
  
  sendsaldoactualbancofecha: data=>axios.post(host+"sendsaldoactualbancofecha",data),
  reverserLiquidar: data=>axios.post(host+"reverserLiquidar",data),
  getDisponibleEfectivoSucursal: data=>axios.post(host+"getDisponibleEfectivoSucursal",data),

  getCajaMatriz: data=>axios.post(host+"getCajaMatriz",data),

  getControlEfec: data=>axios.post(host+"getControlEfec",data),
  delCaja: data=>axios.post(host+"delCaja",data),
  verificarMovPenControlEfecTRANFTRABAJADOR: data=>axios.post(host+"verificarMovPenControlEfecTRANFTRABAJADOR",data),
  verificarMovPenControlEfec: data=>axios.post(host+"verificarMovPenControlEfec",data),
  aprobarRecepcionCaja: data=>axios.post(host+"aprobarRecepcionCaja",data),
  reversarMovPendientes: data=>axios.post(host+"reversarMovPendientes",data),
  setControlEfec: data=>axios.post(host+"setControlEfec",data),
  


  depositarmatrizalbanco: data=>axios.post(host+"depositarmatrizalbanco",data),
  
  saveFacturaLote: data=>axios.post(host+"saveFacturaLote",data),
  sendlistdistribucionselect: data=>axios.post(host+"sendlistdistribucionselect",data),
  
  getGastos: data=>axios.post(host+"getGastos",data),
  getGastosDistribucion: data=>axios.post(host+"getGastosDistribucion",data),
  
  delGasto: data=>axios.post(host+"delGasto",data),
  saveNewGasto: data=>axios.post(host+"saveNewGasto",data),
          

  getSucursales: data=>axios.get(host+"getSucursales",{params:data}),
  getFallas: data=>axios.get(host+"getFallas",{params:data}),

  getVentas: data=>axios.get(host+"getVentas",{params:data}),
  today: data=>axios.get(host+"today",{params:data}),

  setCarrito: data=>axios.post(host+"setCarrito",data),
  
  getPedidosList: data=>axios.post(host+"getPedidosList",data),

  getPedidos: data=>axios.post(host+"getPedidos",data),
  revolverNovedadItemTrans: data=>axios.post(host+"revolverNovedadItemTrans",data),
  getNovedadesPedidosData: data=>axios.post(host+"getNovedadesPedidosData",data),
  
  delPedido: data=>axios.post(host+"delPedido",data),
  getPedido: data=>axios.post(host+"getPedido",data),

  setCtCarrito: data=>axios.post(host+"setCtCarrito",data),
  setDelCarrito: data=>axios.post(host+"setDelCarrito",data),  
  sendPedidoSucursal: data=>axios.post(host+"sendPedidoSucursal",data),
  aprobarRevisionPedido: data=>axios.post(host+"aprobarRevisionPedido",data),
  
  
  openReporteFalla: data=>axios.post(host+"openReporteFalla",data),
  getPagoProveedor: data=>axios.post(host+"getPagoProveedor",data),
  setPagoProveedor: data=>axios.post(host+"setPagoProveedor",data),
  delItemFact: data=>axios.post(host+"delItemFact",data),
  modItemFact: data=>axios.post(host+"modItemFact",data),
  
  
  conciliarCuenta: data=>axios.post(host+"conciliarCuenta",data),
  delFilescxp: data=>axios.post(host+"delFilescxp",data),
  getFilescxp: data=>axios.post(host+"getFilescxp",data),
  showFilescxp: (id) => window.open(host + id, "targed=blank"),


  
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
  verificarproductomaestro: data=>axios.post(host+"verificarproductomaestro",data),
  getotrasopcionesalterno: data=>axios.post(host+"getotrasopcionesalterno",data),
  setotrasopcionesalterno: data=>axios.post(host+"setotrasopcionesalterno",data),
  autovincularPedido: data=>axios.post(host+"autovincularPedido",data),
  removeVinculoCentral: data=>axios.post(host+"removeVinculoCentral",data),
  
  
  guardarmodificarInventarioDici: data=>axios.post(host+"guardarmodificarInventarioDici",data),
  getTareasPendientes: data=>axios.post(host+"getTareasPendientes",data),
  sendTareaRemoverDuplicado: data=>axios.post(host+"sendTareaRemoverDuplicado",data),
  
  
  getEstaInventario: data=>axios.post(host+"getEstaInventario",data),
  getinventario: data=>axios.post(host+"getinventario",data),
  
  getInventarioNovedades: data=>axios.post(host+"getInventarioNovedades",data),
  resolveInventarioNovedades: data=>axios.post(host+"resolveInventarioNovedades",data),
  delInventarioNovedades: data=>axios.post(host+"delInventarioNovedades",data),
  
  
  
  getEstadiscaSelectProducto: data=>axios.post(host+"getEstadiscaSelectProducto",data),
  getInventarioGeneral: data=>axios.post(host+"getInventarioGeneral",data),
  delVinculoSucursal: data=>axios.post(host+"delVinculoSucursal",data),
  
  getBarrasCargaItems: data=>axios.post(host+"getBarrasCargaItems",data),
  
  

  getProveedores: data=>axios.post(host+"getProveedores",data),

  getCategorias: data=>axios.get(host+"getCategorias",{params:data}),
  delCategoria: data=>axios.post(host+"delCategoria",data),
  setCategorias: data=>axios.post(host+"setCategorias",data),

  getMarcas: data=>axios.get(host+"getMarcas",{params:data}),
  delMarca: data=>axios.post(host+"delMarca",data),
  setMarcas: data=>axios.post(host+"setMarcas",data),
  
  setComovamos: data=>axios.post(host+"setComovamos",data),
  
  
  delCuentaPorPagar: data=>axios.post(host+"delCuentaPorPagar",data),
  
  changeAprobarFact: data=>axios.post(host+"changeAprobarFact",data),
  getCatGenerals: data=>axios.get(host+"getCatGenerals",{params:data}),
  delCatGeneral: data=>axios.post(host+"delCatGeneral",data),
  setCatGenerals: data=>axios.post(host+"setCatGenerals",data),

  getDepositos: data=>axios.post(host+"getDepositos",data),
  getFallas: data=>axios.post(host+"getFallas",data),
  setFalla: data=>axios.post(host+"setFalla",data),
  getSucursal: data=>axios.post(host+"getSucursal",data),
  openVerFactura: data=>axios.post(host+"openVerFactura",data),
  
  getsucursalListData: data=>axios.post(host+"getsucursalListData",data),
  getsucursalDetallesData: data=>axios.post(host+"getsucursalDetallesData",data),
  
  delPersonalNomina: data=>axios.post(host+"delPersonalNomina",data),
  getPersonalNomina: data=>axios.post(host+"getPersonalNomina",data),
  setPersonalNomina: data=>axios.post(host+"setPersonalNomina",data),
  activarPersonal: data=>axios.post(host+"activarPersonal",data),
  
  
  delPersonalCargos: data=>axios.post(host+"delPersonalCargos",data),
  getPersonalCargos: data=>axios.post(host+"getPersonalCargos",data),
  setPersonalCargos: data=>axios.post(host+"setPersonalCargos",data),
  getUsuarios: data=>axios.post(host+"getUsuarios",data),
  setUsuario: data=>axios.post(host+"setUsuario",data),
  delUsuario: data=>axios.post(host+"delUsuario",data),
  
  selectPrecioxProveedorSave: data=>axios.post(host+"selectPrecioxProveedorSave",data),
  getPrecioxProveedor: data=>axios.post(host+"getPrecioxProveedor",data),
  
  showImageFact: (id) => window.open(host + "" + id, "targed=blank"),

  
  
  

}

export default db