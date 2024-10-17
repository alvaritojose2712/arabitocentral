/* import { useHotkeys } from 'react-hotkeys-hook'; */

import { cloneDeep } from "lodash";

import { useState, useEffect, useRef } from 'react';
import { render } from 'react-dom';
import db from '../database/database';
import Header from './header';
import SelectSucursal from './selectSucursal';

import VentasComponent from './ventas';

import Notificacion from '../components/notificacion';

import Cargando from '../components/cargando';
import Login from '../components/login';

import Toplabel from './toplabel';

import Panel from './panel/panel'
import PanelOpciones from './panel/panelopciones'
import FechasMain from './panel/fechasmain'

import Cierres from './panel/cierres'
import BalanceCierres from './panel/balanceCierres'
import SucursalListCierres from './panel/sucursallistcierre'
import SucursalDetallesCierres from './panel/sucursaldetallescierres'

import SucursalDetallesGastos from './panel/sucursaldetallesgastos'
import SucursalListGastos from './panel/sucursallistgastos'

import DepartamentosInventario from './panel/departamentosInventario'
import CatGeneral from './panel/catGeneral'
import Marcas from './panel/marcas'

import NavInventario from './panel/navInventario'

import Usuarios from './usuarios';
import Alquileres from './Alquileres';
import Compras from './compras';

import Comprasmenufactsdigital from './Comprasmenufactsdigital';
import ComprasCargarFactsFiscas from './comprascargarfactsfisicas';
import ComprasCargarFactsDigitales from './comprascargarfactsdigitales';
import Comprasmodalselectfactfisicas from './comprasmodalselectfactfisicas';
import ComprasDistribuirFacts from './comprasdistribuirfacts'
import ComprascargarFactsItems from './panel/comprascargarfactsItems'
import ComprasRevision from './comprasrevision'


import Auditoria from './auditoria';
import Proveedores from './proveedores';

import PanelSucursales from './panelSucursales';
import ComoVamos from './comovamos';


import NominaHome from './nomina/nominahome';

import Nomina from './nomina/nomina';

import NominaCargos from './nomina/nominacargos';
import NominaPersonal from './nomina/nominapersonal';

import NominaPagos from './nomina/nominapagos';
import Pedidos from './pedidos';
import Pedir from './pedir';
import Efectivo from './efectivo';
import PorCobrar from './porCobrar';

import AprobacionCajaFuerte from './aprobacioncajafuerte';
import Cuentasporpagar from './cuentasporpagar';
import CuentasporpagarDetalles from './cuentasporpagarDetalles';
import CuentasporpagarPago from './cuentasporpagarPagos';
import EfectivoDisponibleSucursales from './efectivoDisponibleSucursales';

import Gastos from './gastos'
import Inventario from "./inventario";













function Home() {
  // ///In Last//////
  const [view, setView] = useState("")

  const [selectfechaventa, setselectfechaventa] = useState("")

  const [user, setuser] = useState({
    id_usuario: "",
    tipo_usuario: "",
    usuario: "",
    nombre: "",
  })

  ///////////Inventario
  const [permisoExecuteEnter, setpermisoExecuteEnter] = useState(true);
  const [showMisPedido, setshowMisPedido] = useState(true);

  const inputBuscarInventario = useRef(null)


  // const [productosInventario,setProductosInventario] = useState([])
  // const [qBuscarInventario,setQBuscarInventario] = useState("")
  // const [indexSelectInventario,setIndexSelectInventario] = useState(null)

  // const [inpInvbarras,setinpInvbarras] = useState("")
  // const [inpInvcantidad,setinpInvcantidad] = useState("")
  // const [inpInvalterno,setinpInvalterno] = useState("")
  // const [inpInvunidad,setinpInvunidad] = useState("UND")
  // const [inpInvcategoria,setinpInvcategoria] = useState("24")
  // const [inpInvdescripcion,setinpInvdescripcion] = useState("")
  // const [inpInvbase,setinpInvbase] = useState("")
  // const [inpInvventa,setinpInvventa] = useState("")
  // const [inpInviva,setinpInviva] = useState("0")

  // const [inpInvid_proveedor,setinpInvid_proveedor] = useState("")
  // const [inpInvid_marca,setinpInvid_marca] = useState("")
  // const [inpInvid_deposito,setinpInvid_deposito] = useState("")
  // const [inpInvporcentaje_ganancia,setinpInvporcentaje_ganancia] = useState(0)



  // const [proveedordescripcion,setproveedordescripcion] = useState("")
  // const [proveedorrif,setproveedorrif] = useState("")
  // const [proveedordireccion,setproveedordireccion] = useState("")
  // const [proveedortelefono,setproveedortelefono] = useState("")

  // const [subViewInventario,setsubViewInventario] = useState("inventario")

  // const [indexSelectProveedores,setIndexSelectProveedores] = useState(null)



  // const [depositosList,setdepositosList] = useState([])

  // const [facturas,setfacturas] = useState([])

  // const [factqBuscar,setfactqBuscar] = useState("")
  // const [factqBuscarDate,setfactqBuscarDate] = useState("")
  // const [factOrderBy,setfactOrderBy] = useState("id")
  // const [factOrderDescAsc,setfactOrderDescAsc] = useState("desc")
  // const [factsubView,setfactsubView] = useState("buscar")
  // const [factSelectIndex,setfactSelectIndex] = useState(null)
  // const [factInpid_proveedor,setfactInpid_proveedor] = useState("")
  // const [factInpnumfact,setfactInpnumfact] = useState("")
  // const [factInpdescripcion,setfactInpdescripcion] = useState("")
  // const [factInpmonto,setfactInpmonto] = useState("")
  // const [factInpfechavencimiento,setfactInpfechavencimiento] = useState("")

  // const [factInpestatus,setfactInpestatus] = useState(0)

  // const [Invnum,setInvnum] = useState(25)
  // const [InvorderColumn,setInvorderColumn] = useState("id")
  // const [InvorderBy,setInvorderBy] = useState("desc")

  const [subviewProveedores, setsubviewProveedores] = useState("buscar")
  const [subviewCargarProductos, setsubviewCargarProductos] = useState("buscar")

  const [viewProductos, setviewProductos] = useState("")

  const [indexSelectCarrito, setindexSelectCarrito] = useState(null)
  const [showCantidadCarrito, setshowCantidadCarrito] = useState("procesar")

  const [ctSucursales, setctSucursales] = useState([])

  const [id_pedido, setid_pedido] = useState("nuevo")
  const [pedidoList, setpedidoList] = useState([])

  const [qpedidosucursal, setqpedidosucursal] = useState("")
  const [qpedidosucursaldestino,setqpedidosucursaldestino] = useState("")
  const [qpedido, setqpedido] = useState("")
  const [qpedidoDateFrom, setqpedidoDateFrom] = useState("")
  const [qpedidoDateTo, setqpedidoDateTo] = useState("")
  const [qpedidoOrderBy, setqpedidoOrderBy] = useState("id")
  const [qpedidoOrderByDescAsc, setqpedidoOrderByDescAsc] = useState("desc")
  const [pedidos, setpedidos] = useState([])
  const [pedidoData, setpedidoData] = useState(null)
  const [qestadopedido, setqestadopedido] = useState(3)

  ////IMPORT VENTAS
  const [num, setNum] = useState(50)
  const [itemCero, setItemCero] = useState(true)
  const [qProductosMain, setQProductosMain] = useState("")

  const [orderColumn, setOrderColumn] = useState("descripcion")
  const [orderBy, setOrderBy] = useState("asc")

  const [inputaddCarritoFast, setinputaddCarritoFast] = useState("")

  const [dolar, setDolar] = useState("")
  const [peso, setPeso] = useState("")

  const [typingTimeout, setTypingTimeout] = useState(0)

  /*  
  
    useEffect(() => {
      getFacturas(false)
    }, [
      factqBuscar,
      factqBuscarDate,
      factOrderBy,
      factOrderDescAsc
    ])
    useEffect(() => {
      buscarInventario()
    }, [
      Invnum,
      InvorderColumn,
      InvorderBy,
      qBuscarInventario,
    ]);
  
  
    useEffect(() => {
      getProveedores()
    }, [qBuscarProveedor])
    useEffect(() => {
      if (view == "inventario") {
        if (subViewInventario == "fallas") {
          getFallas()
        } else if (subViewInventario == "inventario") {
          getProductos()
        } else if (subViewInventario == "proveedores") {
          getProveedores()
        } else if (subViewInventario == "pedidosCentral") {
          getPedidosCentral()
        }
  
      }
    }, [view, subViewInventario])
  
    useEffect(() => {
      setInputsInventario()
    }, [indexSelectInventario])
  
    useEffect(() => {
      if (subViewInventario == "proveedores") {
        setInputsProveedores()
  
      } else if (subViewInventario == "facturas") {
        getPagoProveedor()
      }
  
    }, [subViewInventario, indexSelectProveedores])
  
    useEffect(() => {
      getEstaInventario()
    }, [
      fechaQEstaInve,
      fechaFromEstaInve,
      fechaToEstaInve,
      orderByEstaInv,
      orderByColumEstaInv]) */



  const [modViewInventario, setmodViewInventario] = useState("unique")

  const [loteIdCarrito, setLoteIdCarrito] = useState(null)
  const refsInpInvList = useRef(null)


  const [valheaderpedidocentral, setvalheaderpedidocentral] = useState("12340005ARAMCAL")
  const [valbodypedidocentral, setvalbodypedidocentral] = useState("12341238123456123456123451234123712345612345612345123412361234561234561234512341235123456123456123451234123412345612345612345")


  const [tipopagoproveedor, settipopagoproveedor] = useState("");
  const [montopagoproveedor, setmontopagoproveedor] = useState("");
  const [pagosproveedor, setpagosproveedor] = useState([]);

  const [fechaQEstaInve, setfechaQEstaInve] = useState("")
  const [fechaFromEstaInve, setfechaFromEstaInve] = useState("")
  const [fechaToEstaInve, setfechaToEstaInve] = useState("")
  const [orderByEstaInv, setorderByEstaInv] = useState("desc")
  const [orderByColumEstaInv, setorderByColumEstaInv] = useState("cantidadtotal")
  const [dataEstaInven, setdataEstaInven] = useState([])

  const [pedidosCentral, setpedidoCentral] = useState([])
  const [indexPedidoCentral, setIndexPedidoCentral] = useState(null)

  const [showaddpedidocentral, setshowaddpedidocentral] = useState(false)

  const [qFallas, setqFallas] = useState("")
  const [orderCatFallas, setorderCatFallas] = useState("proveedor")
  const [orderSubCatFallas, setorderSubCatFallas] = useState("todos")
  const [ascdescFallas, setascdescFallas] = useState("")

  const [productos, setProductos] = useState([])

  const [productosInventario, setProductosInventario] = useState([])

  const [qBuscarInventario, setQBuscarInventario] = useState("")
  const [qBuscarInventarioSucursal, setqBuscarInventarioSucursal] = useState("")
  const [indexSelectInventario, setIndexSelectInventario] = useState(null)

  const [invsuc_itemCero, setinvsuc_itemCero] = useState("")
  const [invsuc_q, setinvsuc_q] = useState("")
  const [invsuc_exacto, setinvsuc_exacto] = useState("")
  const [invsuc_num, setinvsuc_num] = useState("100")
  const [invsuc_orderColumn, setinvsuc_orderColumn] = useState("descripcion")
  const [invsuc_orderBy, setinvsuc_orderBy] = useState("desc")
  const [controlefecSelectGeneral, setcontrolefecSelectGeneral] = useState(1)




  const [controlefecQ,setcontrolefecQ] = useState("")
  const [controlefecQDesde,setcontrolefecQDesde] = useState("")
  const [controlefecQHasta,setcontrolefecQHasta] = useState("")
  const [controlefecNewConcepto,setcontrolefecNewConcepto] = useState("")
  const [controlefecNewFecha,setcontrolefecNewFecha] = useState("")
  
  const [controlefecNewCategoria,setcontrolefecNewCategoria] = useState("")
  const [controlefecNewMonto,setcontrolefecNewMonto] = useState("")
  
  const [controlefecQCategoria,setcontrolefecQCategoria] = useState("")
  const [controlefecNewMontoMoneda,setcontrolefecNewMontoMoneda] = useState("")
  const [transferirpedidoa,settransferirpedidoa] = useState("")

  const [controlefecData, setcontrolefecData] = useState([])
  const [openModalNuevoEfectivo, setopenModalNuevoEfectivo] = useState(false)

  const [personalNomina, setpersonalNomina] = useState([])


  const getControlEfec = () => {
    db.getControlEfec({
        controlefecQ,
        controlefecQDesde,
        controlefecQHasta,
        controlefecQCategoria,
        controlefecSelectGeneral
    }).then(res => {
        setcontrolefecData(res.data)
    })
  }
  const delCaja = (id) => {
      db.delCaja({
          id
      }).then(res => {
          notificar(res)
          getControlEfec()
      })
  }
  const verificarMovPenControlEfecTRANFTRABAJADOR = () => {
      if (confirm("Confirme")) {
          db.verificarMovPenControlEfecTRANFTRABAJADOR({}).then(res=>{
              getControlEfec()
              notificar(res.data)
          })
      }
  }

  const verificarMovPenControlEfec = () => {
      if (confirm("Confirme")) {
          db.verificarMovPenControlEfec({}).then(res=>{
              getControlEfec()
              notificar(res)
          })
      }
  }
  const aprobarRecepcionCaja = (id,type) => {
      if(confirm("¿Está seguro de "+type+" el movimiento?")){
          db.aprobarRecepcionCaja({id,type}).then(res=>{
              getControlEfec()
              notificar(res)
          })
      }
  }

  const reversarMovPendientes = () => {
      if (confirm("¿Realmente desea eliminar los movimientos pendientes?")) {
          if (confirm("¿Seguro/a, Seguro/a?")) {
              if (confirm("No hay marcha atrás!")) {
                  db.reversarMovPendientes({})
                  .then(res=> {
                      getControlEfec()
                      notificar(res.data)
                  })
              }
          }   
      }
  }

  const setControlEfec = (sendCentralData=false) => {

      if (confirm("¿Realmente desea cargar el movimiento?")) {

        console.log(controlefecNewMonto)
        db.setControlEfec({

            fecha: controlefecNewFecha,
            concepto: controlefecNewConcepto,
            categoria: controlefecNewCategoria,
            monto: (controlefecNewMonto),
            controlefecSelectGeneral,
            controlefecNewMontoMoneda,
            sendCentralData,
            transferirpedidoa,
        }).then(res => {
            getControlEfec()
            setopenModalNuevoEfectivo(false)

            setcontrolefecNewConcepto("")
            setcontrolefecNewFecha("")
            setcontrolefecNewCategoria("")
            setcontrolefecNewMonto(0)

            notificar(res.data.msj)
        })
          
      }
  }

  
  
  
  const [inventarioGeneralqsucursal,setinventarioGeneralqsucursal] = useState("")
  const [inpInvbarras, setinpInvbarras] = useState("")
  const [inpInvcantidad, setinpInvcantidad] = useState("")
  const [inpInvalterno, setinpInvalterno] = useState("")
  const [inpInvunidad, setinpInvunidad] = useState("UND")
  const [inpInvcategoria, setinpInvcategoria] = useState("24")
  const [inpInvdescripcion, setinpInvdescripcion] = useState("")
  const [inpInvbase, setinpInvbase] = useState("")
  const [inpInvventa, setinpInvventa] = useState("")
  const [inpInviva, setinpInviva] = useState("0")
  const [inpInvporcentaje_ganancia, setinpInvporcentaje_ganancia] = useState("0")

  const [inpInvLotes, setinpInvLotes] = useState([])

  const [inpInvid_proveedor, setinpInvid_proveedor] = useState("")
  const [inpInvid_marca, setinpInvid_marca] = useState("")
  const [inpInvid_deposito, setinpInvid_deposito] = useState("")

  const [depositosList, setdepositosList] = useState([])
  const [marcasList, setmarcasList] = useState([])

  const [Invnum, setInvnum] = useState(25)
  const [InvorderColumn, setInvorderColumn] = useState("id")
  const [InvorderBy, setInvorderBy] = useState("desc")

  const [proveedordescripcion, setproveedordescripcion] = useState("")
  const [proveedorrif, setproveedorrif] = useState("")
  const [proveedordireccion, setproveedordireccion] = useState("")
  const [proveedortelefono, setproveedortelefono] = useState("")

  const [indexSelectProveedores, setIndexSelectProveedores] = useState(null)

  const [showModalFacturas, setshowModalFacturas] = useState(false)

  const [facturas, setfacturas] = useState([])

  const [factqBuscar, setfactqBuscar] = useState("")
  const [factqBuscarDate, setfactqBuscarDate] = useState("")
  const [factOrderBy, setfactOrderBy] = useState("id")
  const [factOrderDescAsc, setfactOrderDescAsc] = useState("desc")
  const [factsubView, setfactsubView] = useState("buscar")
  const [factSelectIndex, setfactSelectIndex] = useState(null)
  const [factInpid_proveedor, setfactInpid_proveedor] = useState("")
  const [factInpnumfact, setfactInpnumfact] = useState("")
  const [factInpdescripcion, setfactInpdescripcion] = useState("")
  const [factInpmonto, setfactInpmonto] = useState("")
  const [factInpfechavencimiento, setfactInpfechavencimiento] = useState("")

  const [factInpestatus, setfactInpestatus] = useState(0)

  const [modFact, setmodFact] = useState("factura")


  ///////Compras Props
  const [subViewCompras, setsubViewCompras] = useState("resumen")
  const [openSelectProvNewPedComprasCheck, setopenSelectProvNewPedComprasCheck] = useState(false)
  const [NewPedComprasSelectProd, setNewPedComprasSelectProd] = useState(null)

  const [selectPrecioxProveedorProducto, setselectPrecioxProveedorProducto] = useState(null)
  const [selectPrecioxProveedorProveedor, setselectPrecioxProveedorProveedor] = useState(null)
  const [selectPrecioxProveedorPrecio, setselectPrecioxProveedorPrecio] = useState("")
  const [precioxproveedor, setprecioxproveedor] = useState([])

  ///ALQUILERES

  const [alquileresData, setalquileresData] = useState([])
  const [alquileresq, setalquileresq] = useState("")
  const [alquileresq_sucursal, setalquileresq_sucursal] = useState("")

  const [sendalquilerdesc, setsendalquilerdesc] = useState("")
  const [sendalquilermonto, setsendalquilermonto] = useState("")
  const [sendalquilersucursal, setsendalquilersucursal] = useState("")
  const [sendalquilerid, setsendalquilerid] = useState(null)
  const [subviewAlquileres, setsubviewAlquileres] = useState("list")
  
  const [inventariogeneralData, setinventariogeneralData] = useState("list")
  const [inventariogeneralSelectProEsta, setinventariogeneralSelectProEsta] = useState(null)
  const [inventariogeneralProEsta, setinventariogeneralProEsta] = useState(null)
  
  const getEstadiscaSelectProducto = id =>{
    db.getEstadiscaSelectProducto({
      id
    }).then(res=>{
      setinventariogeneralProEsta(res.data)
    })
  }
  const getInventarioGeneral = () => {
    db.getInventarioGeneral({
      invsuc_q,
      invsuc_num,
      invsuc_orderBy,
      inventarioGeneralqsucursal,

      camposAgregadosBusquedaEstadisticas,
      sucursalesAgregadasBusquedaEstadisticas,
    })
    .then(res=>{
      setinventariogeneralData(res.data)
    })
  }
  const [productosInventarioModal, setproductosInventarioModal] = useState([])

  const [InvnumModal, setInvnumModal] = useState("100")
  const [qBuscarInventarioModal, setqBuscarInventarioModal] = useState("")
  const [InvorderColumnModal, setInvorderColumnModal] = useState("descripcion")
  const [InvorderByModal, setInvorderByModal] = useState("asc")
  const [id_sucursal_select_internoModal, setid_sucursal_select_internoModal] = useState(null)

  const buscarInventarioModal = (e=null,id_sucursalForce=false) => {
    setLoading(true)
    if (time != 0) {
        clearTimeout(typingTimeout)
      }
      let time = window.setTimeout(() => {
        db.getinventario({
          itemCero: true,
          num: InvnumModal,
          qProductosMain: qBuscarInventarioModal,
          orderColumn: InvorderColumnModal,
          orderBy: InvorderByModal,
          qBuscarInventarioSucursal: (!id_sucursalForce?id_sucursal_select_internoModal:id_sucursalForce),
        }).then(res => {
          setproductosInventarioModal(res.data)
          setLoading(false)
        })
      }, 150)
      setTypingTimeout(time)
  }

  const delVinculoSucursal = id_vinculo => {
    if (confirm("Confirme")) {
      if (confirm("Confirme DE NUEVO")) {
        db.delVinculoSucursal({id_vinculo}).then(res=>{
          if (res.data.estado) {
            getInventarioGeneral()
            notificar(res)
          }
        })
      }
    }
  }

  
  const delAlquiler = () => {
    if (confirm("¿Desea realmente eliminar?")) {
      db.delAlquiler({id:sendalquilerid}).then(res=>{
        notificar(res)
        if (res.data.estado) {
          setsendalquilerid(null)
          setsubviewAlquileres("list")
          getAlquileres()
        }
      })
    }
  }
  
  const setNewAlquiler = () => {
    db.setNewAlquiler({
      sendalquilerdesc,
      sendalquilermonto,
      sendalquilersucursal,
      sendalquilerid,
    })
    .then(res=>{
      notificar(res)
      if (res.data.estado) {
        setsendalquilerid(null)
        setsubviewAlquileres("list")
        getAlquileres()
      }
    })
  }

  const getAlquileres = () => {
    db.getAlquileres({
      alquileresq,
      alquileresq_sucursal,
    }).then(res=>{
      if (res.data) {
        setsendalquilerid(null)
        if (res.data.data.length) {
          setalquileresData(res.data)
        }else{
          setalquileresData([])
        }
        
      }
    })
  }




  /////GARANTIAS
  const [garantiasData,setgarantiasData] = useState([])
  const [garantiaq,setgarantiaq] = useState("")
  const [garantiaqsucursal,setgarantiaqsucursal] = useState("")
  const getGarantias = () => {

    db.getGarantias({
      garantiaq,
      garantiaqsucursal,
    }).then(res=>{
      setgarantiasData(res.data)
    })
  }
  ///END GARANTIAS


  const [dataPedidoAnulacionAprobacion, setdataPedidoAnulacionAprobacion] = useState([])
  const [qdesdePedidoAnulacionAprobacion, setqdesdePedidoAnulacionAprobacion] = useState("")
  const [qhastaPedidoAnulacionAprobacion, setqhastaPedidoAnulacionAprobacion] = useState("")
  const [qnumPedidoAnulacionAprobacion, setqnumPedidoAnulacionAprobacion] = useState("")
  const [qestatusPedidoAnulacionAprobacion, setqestatusPedidoAnulacionAprobacion] = useState(0)
  const [sucursalPedidoAnulacionAprobacion, setsucursalPedidoAnulacionAprobacion] = useState("")

  const getAprobacionPedidoAnulacion = () => {
    db.getAprobacionPedidoAnulacion({
      qdesdePedidoAnulacionAprobacion,
      qhastaPedidoAnulacionAprobacion,
      qnumPedidoAnulacionAprobacion,
      qestatusPedidoAnulacionAprobacion,
      sucursalPedidoAnulacionAprobacion,
    }).then(res=>{
      setdataPedidoAnulacionAprobacion(res.data)
    })
  }

  const setAprobacionPedidoAnulacion = (id,tipo) => {
    db.setAprobacionPedidoAnulacion({
      id,
      tipo,
    }).then(res=>{
      notificar(res)
      getAprobacionPedidoAnulacion()
    })
  }
  
  ///Proveedores Props
  
  const [qBuscarProveedor, setQBuscarProveedor] = useState("")
  const [proveedoresList, setProveedoresList] = useState([])
  
  const [factInpImagen, setfactInpImagen] = useState("");
  const [factInpProveedor, setfactInpProveedor] = useState("");
  const [factNumfact, setfactNumfact] = useState("");
  const sendComprasFats = (event) => {
    event.preventDefault()
    const formData = new FormData();
    formData.append("imagen",factInpImagen);
    formData.append("id_proveedor",factInpProveedor);
    formData.append("numfact",factNumfact);
    db.sendComprasFats(
        formData
    ).then((res) => {
        notificar(res.data.msj);
        if (res.data.id) {
          console.log(res.data.id)
          seleccionarFilecxpFun(res.data.id)
          getFilescxp()
        }
    });
  } 


  const [colorSucursalData,setcolorSucursalData] = useState({})
  const colorFun = (str) => {
    var stringHexNumber = (                       // 1
      parseInt(                                 // 2
          parseInt(str, 36)  // 3
              .toExponential()                  // 4
              .slice(2,-5)                      // 5
      , 10) & 0xFFFFFF                          // 6
    ).toString(16).toUpperCase(); 

    return (stringHexNumber+"000").slice(0,6)
  }

  const colorSucursal = code => {
    if(colorSucursalData[code]){
      return colorSucursalData[code]
    }
    return ""
  }


  const getBancoName = (code) => {
    if (opcionesMetodosPago.length) {
      let fil = opcionesMetodosPago.filter(e=>code==e.codigo)
      if (fil.length) {
        return fil[0].descripcion
      }
    }
    return "N/A"
  }
  const colors = {
    "BIOPAGO 1": "rgb(200, 109, 109)",
    "BIOPAGO": "rgb(200, 109, 109)",
    "PUNTO 0": "rgb(245, 222, 35)",
    "PUNTO 1": "rgb(245, 222, 35)",
    "PUNTO 2": "rgb(245, 222, 35)",
    "PUNTO 3": "rgb(245, 222, 35)",
    "PUNTO 4": "rgb(245, 222, 35)",
    "PUNTO 5": "rgb(245, 222, 35)",
    "PUNTO 6": "rgb(245, 222, 35)",
    "PUNTO 7": "rgb(245, 222, 35)",
    "PUNTO 8": "rgb(245, 222, 35)",
    "PUNTO 9": "rgb(245, 222, 35)",
    "PUNTO 10": "rgb(245, 222, 35)",
    "PUNTO 11": "rgb(245, 222, 35)",
    "PUNTO 12": "rgb(245, 222, 35)",
    "PUNTO 13": "rgb(245, 222, 35)",
    "PUNTO 14": "rgb(245, 222, 35)",
    "PUNTO": "rgb(245, 222, 35)",
    "Transferencia": "#0091ff",

    "EFECTIVO": ["#06f977", "#000000"],
    "0175": ["#ff90b3", "#000000"],
    "0191": ["#ffd102", "#000000"],
    "0151": ["#14ffe7", "#000000"],
    "0114": ["#d8ff14", "#000000"],
    "BINANCE": ["#836901", "#000000"],
    "AirTM": ["#6d093b", "#000000"],
    "0105": ["#0091ff", "#000000"],
    "0102": ["#d70808", "#fff"],
    "0108": ["#0c3868", "#fff"],
    "0134": ["rgb(10, 132, 120)", "#fff"],
    "0134 - PERSONAL OMAR": ["rgb(10, 132, 120)", "#fff"],
    "ZELLE": ["#6d093b", "#fff"],
  }

  const colorCat = {
    1:	{color:"#d9ead3",	desc:"CAJA CHICA: EFECTIVO ADICIONAL"},
    2:	{color:"#c9daf8",	desc:"CAJA CHICA: CENA NOCTURNA"},
    3:	{color:"#c9daf8",	desc:"CAJA CHICA: TORTA DE CUMPLEAÑOS"},
    4:	{color:"#c9daf8",	desc:"CAJA CHICA: ALMUERZO DE TRABAJADOR"},
    5:	{color:"#c9daf8",	desc:"CAJA CHICA: EXAMENES MEDICOS"},
    6:	{color:"#00ffff",	desc:"CAJA CHICA: LIMPIEZA: CLORO, JABON, AROMATIZANTE, CERA"},
    7:	{color:"#00ffff",	desc:"CAJA CHICA: LIMPIEZA: COLETO, CEPILLOS, PALAS, TRAPOS, PAPEL HIGIENICO"},
    8:	{color:"#fff2cc",	desc:"CAJA CHICA: VASOS PARA CAFE"},
    9:	{color:"#fff2cc",	desc:"CAJA CHICA: VASOS PARA PANELADA"},
    10:	{color:"#d9ead3",	desc:"CAJA CHICA: BOLSAS"},
    11:	{color:"#f4cccc",	desc:"CAJA CHICA: AZUCAR"},
    12:	{color:"#f4cccc",	desc:"CAJA CHICA: CAFE"},
    13:	{color:"#f4cccc",	desc:"CAJA CHICA: PANELADA O JUGO"},
    14:	{color:"#fce5cd",	desc:"CAJA CHICA: PAPELERIA: HOJAS, CARTULINA, FOTOCOPIAS, MARCADORES"},
    15:	{color:"#6d9eeb",	desc:"CAJA CHICA: AGUA: BOTELLON"},
    16:	{color:"#6d9eeb",	desc:"CAJA CHICA: AGUA: HIELO"},
    17:	{color:"#6d9eeb",	desc:"CAJA CHICA: AGUA: CISTERNA"},
    18:	{color:"#d9d2e9",	desc:"CAJA CHICA: SUMINISTROS CASA IMPORTADOS "},
    19:	{color:"#d9d2e9",	desc:"CAJA CHICA: CALETEROS "},
    20:	{color:"#d9d2e9",	desc:"CAJA CHICA: COLABORACION SUCURSAL "},
    21:	{color:"#dd7e6b",	desc:"CAJA CHICA: REPARACIONES Y MANTENIMIENTO"},
    22:	{color:"#fff2cc",	desc:"CAJA CHICA: TRANSPORTE: TAXI Y MOTOTAXI "},
    23:	{color:"#fff2cc",	desc:"CAJA CHICA: TRANSPORTE: COMBUSTIBLE "},
    24:	{color:"#fff2cc",	desc:"CAJA CHICA: TRANSPORTE: REPARACION DE VEHICULOS "},
    25:	{color:"#f4cccc",	desc:"CAJA CHICA: TRASPASO A CAJA FUERTE"},
    26:	{color:"#00ff00",	desc:"INGRESO DESDE CIERRE"},
    27:	{color:"#d9ead3",	desc:"CAJA FUERTE: EFECTIVO ADICIONAL"},
    28:	{color:"#ffff00",	desc:"CAJA FUERTE: NOMINA ABONO"},
    29:	{color:"#ffff00",	desc:"CAJA FUERTE: BONO PRODUCTIVIDAD "},
    30:	{color:"#ffff00",	desc:"CAJA FUERTE: NOMINA PRESTAMO "},
    31:	{color:"#b4a7d6",	desc:"CAJA FUERTE: SERVICIOS: ELECTRICIDAD "},
    32:	{color:"#b4a7d6",	desc:"CAJA FUERTE: SERVICIOS: AGUA "},
    33:	{color:"#b4a7d6",	desc:"CAJA FUERTE: SERVICIOS: INTERNET "},
    34:	{color:"#b6d7a8",	desc:"CAJA FUERTE: ALQUILER "},
    35:	{color:"#fff2cc",	desc:"CAJA FUERTE: TALONARIOS, SELLOS, ETC "},
    36:	{color:"#e6b8af",	desc:"CAJA FUERTE: COLABORACIONES GENERAL (TODAS SUCURSALES)"},
    37:	{color:"#dd7e6b",	desc:"CAJA FUERTE: TRANSPORTE: COMBUSTIBLE (TODAS SUCURSALES)"},
    38:	{color:"#dd7e6b",	desc:"CAJA FUERTE: TRANSPORTE: REPARACION DE VEHICULOS (TODAS SUCURSALES)"},
    39:	{color:"#dd7e6b",	desc:"CAJA FUERTE: TRANSPORTE: VIATICOS Y PEAJES (TODAS SUCURSALES)"},
    40:	{color:"#ff0000",	desc:"CAJA FUERTE: PAGO PROVEEDOR"},
    41:	{color:"#6aa84f",	desc:"CAJA FUERTE: FDI"},
    42:	{color:"#fff2cc",	desc:"CAJA FUERTE: TRASPASO A CAJA MATRIZ (RAID RETIRA)"},
    43:	{color:"#d9d9d9",	desc:"CAJA FUERTE: TRANSFERENCIA TRABAJADOR"},
    44:	{color:"#f4cccc",	desc:"CAJA FUERTE: TRASPASO A CAJA CHICA"},
    45:	{color:"#b7b7b7",	desc:"CAJA FUERTE: EGRESO TRANSFERENCIA SUCURSAL"},
    46:	{color:"#b7b7b7",	desc:"CAJA FUERTE: INGRESO TRANSFERENCIA SUCURSAL"},
    
    
    47: {color:"#fff2cc", desc:"CAJA MATRIZ: ARANCELES MUNICIPALES"},
    48: {color:"#fff9cc", desc:"CAJA MATRIZ: SENIAT"},
    49: {color:"#fff8cc", desc:"CAJA MATRIZ: CREDITO BANCARIO"},
    50: {color:"#fff7cc", desc:"CAJA MATRIZ: COMISION PUNTO DE VENTA"},
    51: {color:"#fb9f68", desc:"CAJA MATRIZ: COMISION TRANSFERENCIA INTERBANCARIA O PAGO MOVIL"},
    52: {color:"#fff5cc", desc:"CAJA MATRIZ: ASEO"},
    53: {color:"#fff4cc", desc:"CAJA FUERTE: PUBLICIDAD"},
    54: {color:"#e8f0be", desc:"CAJA MATRIZ: INGRESO TRASPASO ENTRE CUENTAS"},
    55: {color:"#ffe4e1", desc:"CAJA MATRIZ: EGRESO TRASPASO ENTRE CUENTAS"},
    56: {color:"#fff1cc", desc:"CAJA MATRIZ: DEVOLUCION CLIENTE"},
    57: {color:"#fff1cc", desc:"CAJA MATRIZ: BOMBEROS"},
    58:	{color:"#ffff01", desc:"CAJA MATRIZ: IVSS"},
    59:	{color:"#ffff01", desc:"CAJA MATRIZ: BANAVIH"},
    60:	{color:"#ffff01", desc:"CAJA MATRIZ: INCES"},
    61:	{color:"#ffff00", desc:"CAJA FUERTE: NOMINA PRESTACIONES SOCIALES"},

    62:	{color:"#ffff00", desc:"CAJA FUERTE: SERVICIOS: VIGILANCIA"},
    63:	{color:"#ffff00", desc:"CAJA MATRIZ: COMISION DIFERENCIA DE TASA"},
    64:	{color:"#ffff00", desc:"CAJA MATRIZ: COMPRA DE DIVISAS"},
    65:	{color:"#ffff00", desc:"CAJA MATRIZ: DEPOSITO A BANCO"},

    66:	{color:"#d9ead3",	desc:"CAJA MATRIZ: TRANSFERENCIA ADICIONAL"},
    
    67:	{color:"#d9ead3",	desc:"PERSONAL"},
    68:	{color:"#d9ead3",	desc:"EQUIPOS DE COMPUTACION Y CONSUMIBLES"},
    69:	{color:"#d9ead3",	desc:"PÓLIZA DE SEGURO"},
    70:	{color:"#d9ead3",	desc:"CAJA FUERTE: NOMINA BONO VACACIONAL"},

  }
  
  
  const colorCatGeneral = {
    0: {color:"#fca7a7", desc: "PAGO A PROVEEDORES"},
    1: {color:"#00ff00", desc: "INGRESO"},
    2: {color:"#ff9900", desc: "GASTO"},
    3: {color:"#b45f06", desc: "GASTO GENERAL"},
    4: {color:"#999999", desc: "TRANSFERENCIA TRABAJADOR"},
    5: {color:"#a3a3a3", desc: "MOVIMIENTO NULO INTERNO"},
    6: {color:"#fff2cc", desc: "CAJA MATRIZ"},
    7: {color:"#b7b7b7", desc: "FDI"},
    8: {color:"#6aa84f", desc: "INGRESO EXTERNO"},
    9: {color:"#93c47d", desc: "INGRESO INTERNO"},
    10: {color:"#999999", desc: "TRANSFERENCIA EFECTIVO SUCURSAL"},

    20: {color:"#ff9900", desc: "GASTO - CAJA CHICA"},
    30: {color:"#b45f06", desc: "GASTO GENERAL - CAJA CHICA"},

    21: {color:"#ff9900", desc: "GASTO - CAJA FUERTE"},
    31: {color:"#b45f06", desc: "GASTO GENERAL - CAJA FUERTE"},
  }
  const colorIngresoegre = {
    0:	{color:"#f4cccc", desc:"EGRESO"}, 
    1:	{color:"#d9ead3", desc:"INGRESO"}, 
    2:	{color:"#fff2cc", desc:"TRASPASO"}, 
    3:	{color:"#ffd966", desc:"TRASPASO EXTERNO"}, 
    4:	{color:"#93c47d", desc:"INGRESO NULO"}, 
  }

  const colorvariable_fijo = {
    0:	{color:"#f4cccc", desc:"VARIABLES"}, 
    1:	{color:"#d9ead3", desc:"FIJOS"}, 
    2:	{color:"#fff2cc", desc:"OTROS"}, 
    3:	{color:"#ffd966", desc:"OTROS"}, 
    4:	{color:"#93c47d", desc:"OTROS"}, 
  }
  const colorsGastosCat = (id,cat,tipo) => {
    try {
      switch (cat) {
        case "cat":
          return colorCat[id][tipo]  
        break;
        case "catgeneral":
          return  colorCatGeneral[id][tipo]  
        break;
        case "ingreso_egreso":
          return colorIngresoegre[id][tipo]  
        break;
        case "variable_fijo":
          return colorvariable_fijo[id][tipo]  
        break;
      }
      
    } catch (error) {
      console.log(error)
      return "SIN DESC "+id+" "+cat+" "+tipo
    }
  }



  const openReporteFalla = (id) => {
    if (id) {
      db.openReporteFalla(id)

    }
  }
  const getPagoProveedor = () => {
    if (proveedoresList[indexSelectProveedores]) {
      setLoading(true)
      db.getPagoProveedor({
        id_proveedor: proveedoresList[indexSelectProveedores].id,
      }).then(res => {
        setLoading(false)
        setpagosproveedor(res.data)
      })
    }
  }
  const setPagoProveedor = e => {
    e.preventDefault()
    if (tipopagoproveedor && montopagoproveedor) {
      if (proveedoresList[indexSelectProveedores]) {
        db.setPagoProveedor({
          tipo: tipopagoproveedor,
          monto: montopagoproveedor,
          id_proveedor: proveedoresList[indexSelectProveedores].id,
        }).then(res => {
          getPagoProveedor()
          notificar(res)
        })
      }
    }

  }

  ///DICI
  const [qInventarioNovedades,setqInventarioNovedades] = useState("")
  const [qFechaInventarioNovedades,setqFechaInventarioNovedades] = useState("")
  const [qFechaHastaInventarioNovedades,setqFechaHastaInventarioNovedades] = useState("")
  const [qSucursalInventarioNovedades,setqSucursalInventarioNovedades] = useState("")
  const [inventarioNovedadesData,setinventarioNovedadesData] = useState([])

  const getInventarioNovedades = () => {
    db.getInventarioNovedades({
      qInventarioNovedades,
      qFechaInventarioNovedades,
      qFechaHastaInventarioNovedades,
      qSucursalInventarioNovedades,
    }).then(res=>{
      setinventarioNovedadesData(res.data)
    })
  }

  const resolveInventarioNovedades = (id) => {
    db.resolveInventarioNovedades({id}).then(res=>{
      getInventarioNovedades()
    })
  }
  const delInventarioNovedades = id => {
    if (confirm("Confirme")) {
      db.delInventarioNovedades({id}).then(res=>{
        notificar(res)
      })
    }
  }




  ///END DICI

  const getPedidosCentral = () => {
    setLoading(true)
    db.getPedidosCentral({}).then(res => {
      setLoading(false)
      if (res.data) {
        if (res.data.length) {
          setpedidoCentral(res.data)
        }
        if (res.data.msj) {
          notificar(res)
        }
      }
    })
  }
  const selectPedidosCentral = e => {

    try {
      let index = e.currentTarget.attributes["data-index"].value
      let tipo = e.currentTarget.attributes["data-tipo"].value

      let pedidosCentral_copy = cloneDeep(pedidosCentral)

      if (tipo == "select") {
        if (pedidosCentral_copy[indexPedidoCentral].items[index].aprobado === true) {

          pedidosCentral_copy[indexPedidoCentral].items[index].aprobado = false
          pedidosCentral_copy[indexPedidoCentral].items[index].ct_real = ""

        } else if (pedidosCentral_copy[indexPedidoCentral].items[index].aprobado === false) {

          delete pedidosCentral_copy[indexPedidoCentral].items[index].aprobado
          delete pedidosCentral_copy[indexPedidoCentral].items[index].ct_real

        } else if (typeof (pedidosCentral_copy[indexPedidoCentral].items[index].aprobado) === "undefined") {
          pedidosCentral_copy[indexPedidoCentral].items[index].aprobado = true

        }

      } else if (tipo == "changect_real") {
        pedidosCentral_copy[indexPedidoCentral].items[index].ct_real = number(e.currentTarget.value, 4)
      }

      setpedidoCentral(pedidosCentral_copy)



      // console.log(pedidosCentral_copy)

    } catch (err) {
      console.log(err)
    }
  }
  const checkPedidosCentral = () => {
    if (indexPedidoCentral !== null && pedidosCentral) {
      if (pedidosCentral[indexPedidoCentral]) {
        setLoading(true)
        db.checkPedidosCentral({ pedido: pedidosCentral[indexPedidoCentral] }).then(res => {
          setLoading(false)

          notificar(res)
          if (res.data.estado) {
            getPedidosCentral()
          }
        })
      }
    }
  }
 
  const delFalla = e => {
    if (confirm("¿Desea Eliminar?")) {
      let id = e.currentTarget.attributes["data-id"].value
      db.delFalla({ id }).then(res => {
        notificar(res)
        getFallas()
      })
    }
  }
  const setFactura = e => {
    e.preventDefault()
    setLoading(true)

    let id = null

    if (factSelectIndex != null) {
      if (facturas[factSelectIndex]) {
        id = facturas[factSelectIndex].id
      }
    }
    db.setFactura({
      factInpid_proveedor,
      factInpnumfact,
      factInpdescripcion,
      factInpmonto,
      factInpfechavencimiento,
      factInpestatus,
      id
    }).then(res => {
      notificar(res)
      getFacturas()
      setLoading(false)
      if (res.data.estado) {
        setfactsubView("buscar")
        setfactSelectIndex(null)
      }

    })
  }
  const delFactura = e => {
    let id = null

    if (factSelectIndex != null) {
      if (facturas[factSelectIndex]) {
        id = facturas[factSelectIndex].id
      }
    }
    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delFactura({ id }).then(res => {
        setLoading(false)
        getFacturas()
        notificar(res)
        if (res.data.estado) {
          setfactsubView("buscar")
          setfactSelectIndex(null)
        }
      })

    }
  }
  const saveFactura = () => {

    if (facturas[factSelectIndex]) {
      let id = facturas[factSelectIndex].id
      let monto = facturas[factSelectIndex].summonto_base_clean
      db.saveMontoFactura({ id, monto }).then(e => {
        getFacturas(false)
      })
    }
  }

  const delProveedor = e => {
    let id;
    if (indexSelectProveedores != null) {
      if (proveedoresList[indexSelectProveedores]) {
        id = proveedoresList[indexSelectProveedores].id
      }
    }
    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delProveedor({ id }).then(res => {
        setLoading(false)
        getProveedores()
        notificar(res)

        if (res.data.estado) {
          setIndexSelectProveedores(null)
        }
      })

    }

  }
  const delProducto = e => {
    let id;
    if (indexSelectInventario != null) {
      if (productosInventario[indexSelectInventario]) {
        id = productosInventario[indexSelectInventario].id
      }
    }
    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delProducto({ id }).then(res => {
        setLoading(false)
        buscarInventario()
        notificar(res)
        if (res.data.estado) {
          setIndexSelectInventario(null)
        }
      })

    }
  }
  const getFacturas = (clean = true) => {

    if (time != 0) {
      clearTimeout(typingTimeout)
    }

    let time = window.setTimeout(() => {
      setLoading(true)
      db.getFacturas({
        factqBuscar,
        factqBuscarDate,
        factOrderBy,
        factOrderDescAsc
      }).then(res => {
        setLoading(false)
        setfacturas(res.data)

        if (res.data.length === 1) {
          setfactSelectIndex(0)
        }

        if (clean) {
          setfactSelectIndex(null)
        }
      })

    }, 100)
    setTypingTimeout(time)

  }

  const setInputsProveedores = () => {
    if (proveedoresList[indexSelectProveedores]) {
      let obj = proveedoresList[indexSelectProveedores]

      setproveedordescripcion(obj.descripcion)
      setproveedorrif(obj.rif)
      setproveedordireccion(obj.direccion)
      setproveedortelefono(obj.telefono)


    }
  }
  const guardarNuevoProducto = e => {
    e.preventDefault()
    setLoading(true)

    let id = null

    if (indexSelectInventario != null) {
      if (productosInventario[indexSelectInventario]) {
        id = productosInventario[indexSelectInventario].id
      }
    }

    let id_factura = null

    if (factSelectIndex != null) {
      if (facturas[factSelectIndex]) {
        id_factura = facturas[factSelectIndex].id
      }
    }

    db.guardarNuevoProducto({
      id,
      inpInvbarras,
      inpInvcantidad,
      inpInvalterno,
      inpInvunidad,
      inpInvcategoria,
      inpInvdescripcion,
      inpInvbase,
      inpInvventa,
      inpInviva,
      inpInvid_proveedor,
      inpInvid_marca,
      inpInvid_deposito,
      inpInvporcentaje_ganancia,
      id_factura,

      inpInvLotes,

    }).then(res => {
      notificar(res)

      setLoading(false)

      if (res.data.estado) {
        buscarInventario()
        getFacturas(false)

        setinpInvbarras("")
        setinpInvcantidad("")
        setinpInvalterno("")
        setinpInvunidad("UND")
        setinpInvcategoria("24")
        setinpInvdescripcion("")
        setinpInvbase("")
        setinpInvventa("")
        setinpInviva("0")
        setinpInvid_marca("")
      }
    })
  }
  const setProveedor = e => {
    setLoading(true)
    e.preventDefault()

    let id = null

    if (indexSelectProveedores != null) {
      if (proveedoresList[indexSelectProveedores]) {
        id = proveedoresList[indexSelectProveedores].id
      }
    }
    db.setProveedor({
      proveedordescripcion,
      proveedorrif,
      proveedordireccion,
      proveedortelefono,
      id
    }).then(res => {
      notificar(res)
      getProveedores()
      setLoading(false)

    })
  }
  const changeModLote = (val, i, id, type, name = null) => {

    let lote = cloneDeep(inpInvLotes)

    switch (type) {
      case "update":
        if (lote[i].type != "new") {
          lote[i].type = "update"
        }
        break;
      case "delModeUpdateDelete":
        delete lote[i].type
        break;
      case "delNew":
        lote = lote.filter((e, ii) => ii !== i)
        break;
      case "changeInput":
        lote[i][name] = val
        break;

      case "delMode":
        lote[i].type = "delete"
        let id_replace = 0
        lote[i].id_replace = id_replace
        break;
    }
    setinpInvLotes(lote)
  }
  const reporteInventario = () => {
    db.openReporteInventario()
  }
  const modItemFact = (id,campo) => {
    let valor = window.prompt("Valor para "+campo)
    if (valor) {
      db.modItemFact({id,campo,valor}).then(res=>{
        notificar(res)
        if (res.data.estado) {
          buscarInventario()
        }
      })
    }
  }
  const delItemFact = (id) => {
    if (confirm("Confirme")) {
      db.delItemFact({id}).then(res=>{
        notificar(res)
        if (res.data.estado) {
          selectCuentaPorPagarProveedorDetallesFun()
        }
      })
    }
  }
  const guardarNuevoProductoLote = () => {
    try {
      let id_factura = null
      if (facturaSelectAddItems) {
        id_factura = facturaSelectAddItems
      }
      let lotesFil = productosInventario.filter(e => {
        if (e) {
          if (e.type) {
            return true
          }
        }
        return false;
      })
  
      if (lotesFil.length) {
        db.guardarNuevoProductoLote({ lotes: lotesFil, id_factura }).then(res => {
          selectCuentaPorPagarProveedorDetallesFun()
          notificar(res);
          if (res.data.estado) {
            buscarInventario()
          }
        })
      } else {
        alert("¡Error con los campos! Algunos pueden estar vacíos" + JSON.stringify(checkempty))
      }
    } catch (error) {
      console.log(productosInventario,"produc")
      console.log(error,"error")
    }


  }

  const verificarproductomaestro = () => {
    if (confirm("Confirme VINCULO PRODUCTO MAESTRO")) {
      
      let lotesFil = productosInventario.map((e,i)=>{e.index = i;return e}).filter(e => e.type)
      db.verificarproductomaestro({
        lotes: lotesFil,
  
      }).then(res=>{
        let dataModify = res.data
        let clone = cloneDeep(productosInventario)
        let cloneModify = clone.map((ee,ii)=>{
          ee = dataModify.filter(e=>e.index==ii)[0]
          return ee
        }).filter(e=>e)
        setProductosInventario(cloneModify)
      })
    }
  }
  
  const [dataotrasopcionesalterno,setdataotrasopcionesalterno] = useState([])
  const [indexotrasopcionesalterno,setindexotrasopcionesalterno] = useState(null)
  
  const getotrasopcionesalterno = index =>{
    if (indexotrasopcionesalterno==index) {
      setindexotrasopcionesalterno(null)
    }else{
      setindexotrasopcionesalterno(index)
      let fil = productosInventario.filter((e,i)=>index==i)
      if (fil.length) {
        setdataotrasopcionesalterno([])
        db.getotrasopcionesalterno({
          alterno: fil[0]
        }).then(res=>{
          setdataotrasopcionesalterno(res.data)
        })
      }else{
        console.log("getotrasopcionesalterno",fil)
      }
    }
  }

  const setotrasopcionesalterno = (indexmodify,idproducto) =>{
    let fil = dataotrasopcionesalterno.filter(e=>e.id==idproducto)
    
    if (fil.length) {
      let clone = cloneDeep(productosInventario)
      let datanew = fil[0]
      
      setProductosInventario(clone.map((e,i)=>{
        if (indexmodify==i) {
          
          e.id = null
          e.codigo_barras_antes = e.codigo_barras_antes?e.codigo_barras_antes:e.codigo_barras
          e.descripcion_antes = e.descripcion_antes?e.descripcion_antes:e.descripcion

          e.codigo_barras = datanew.codigo_barras
          e.descripcion = datanew.descripcion

          e.unidad = datanew.unidad
          e.id_categoria = datanew.id_categoria
          e.id_catgeneral = datanew.id_catgeneral
          e.iva = datanew.iva
          e.id_marca = datanew.id_marca

          e.codigo_proveedor2 = datanew.codigo_proveedor2
          e.id_deposito = datanew.id_deposito
          e.porcentaje_ganancia = datanew.porcentaje_ganancia
          /* e.precio_base = datanew.precio_base
          e.precio = datanew.precio */
          e.precio1 = datanew.precio1
          e.precio2 = datanew.precio2
          e.precio3 = datanew.precio3
          e.n1 = datanew.n1
          e.n2 = datanew.n2
          e.n3 = datanew.n3
          e.n4 = datanew.n4
          e.n5 = datanew.n5
          e.id_proveedor = datanew.id_proveedor
          e.stockmin = datanew.stockmin
          e.stockmax = datanew.stockmax

          e.type_vinculo = datanew.sucursal.codigo
          
        }
        return e
      }))
    }else{
      console.log("setotrasopcionesalterno",fil)
    }

  }

  const autovincularPedido = id_cuenta => {
    if (confirm("CONFIRME AUTOVINCULO")) {
      db.autovincularPedido({id_cuenta})
      .then(res=>{
        if (res.data.estado) {
          selectCuentaPorPagarProveedorDetallesFun()
        }
      })
    }
  }
  

  
  const changeInventario = (val, i, type, name = null) => {
    let obj = cloneDeep(productosInventario)

    switch (type) {
      case "update":
        if (obj[i].type != "new") {
          obj[i].type = "update"
          obj[i].cantidad = ""
        }
        break;
      case "delModeUpdateDelete":
        delete obj[i].type
        break;
      case "delNew":
        obj = obj.filter((e, ii) => ii !== i)
        break;
      case "changeInput":
        obj[i][name] = val
        break;
      case "add":
        let pro = ""

        if (facturas[factSelectIndex]) {
          pro = facturas[factSelectIndex].proveedor.id
        }
        let newObj = [{
          id: null,
          codigo_proveedor: "",
          codigo_barras: "",
          descripcion: "",
          id_categoria: "",
          id_marca: "",
          
          id_catgeneral: "",
          unidad: "UND",
          cantidad: "",
          basef: "",
          precio_base: "",
          precio: "",
          iva: "0",

          type: "new",

        }]

        obj = newObj.concat(obj)
        break;

      case "delMode":
        obj[i].type = "delete"
        let id_replace = 0
        obj[i].id_replace = id_replace
        break;
    }
    setProductosInventario(obj)
  }

  const changeInventarioModificarDici = (val, i, type, name = null) => {
    let obj = cloneDeep(productosInventario)

    switch (type) {
      case "update":
        if (obj[i].type != "new") {
          obj[i].type = "update"
        }
        break;
      case "delModeUpdateDelete":
        delete obj[i].type
        break;
      case "delNew":
        obj = obj.filter((e, ii) => ii !== i)
        break;
      case "changeInput":
        obj[i][name] = val
        break;
      case "add":
        let pro = ""

        
        let newObj = [{
          id: null,
          id_sucursal: 13,
          codigo_barras: "",
          codigo_proveedor: "",
          codigo_proveedor2: "",
          id_deposito: "",
          unidad: "",
          descripcion: "",
          iva: "",
          porcentaje_ganancia: "",
          precio_base: "",
          precio: "",
          precio1: "",
          precio2: "",
          precio3: "",
          bulto: "",
          cantidad: "",
          push: 0,
          id_vinculacion: "",
          n1: "",
          n2: "",
          n3: "",
          n4: "",
          n5: "",
          id_proveedor: "",
          id_categoria: "",
          id_catgeneral: "",
          id_marca: "",
          id_marca: "",
          stockmin: "",
          stockmax: "",

          type: "new",

        }]

        obj = newObj.concat(obj)
        break;

      case "delMode":
        obj[i].type = "delete"
        break;
    }
    setProductosInventario(obj)
  }

  
  
  /////TAREAS PEN

  
  const [listselectEliminarDuplicados, setlistselectEliminarDuplicados] = useState([])
  const [qTareaPendienteFecha,setqTareaPendienteFecha] = useState("")
  const [qTareaPendienteSucursal,setqTareaPendienteSucursal] = useState("")
  
  const [qTareaPendienteEstado,setqTareaPendienteEstado] = useState(0)
  const [qTareaPendienteNum,setqTareaPendienteNum] = useState(50)

  const [tareasPendientesData,settareasPendientesData] = useState([])
  
  const guardarmodificarInventarioDici = () => {
    let lotesFil = productosInventario.filter(e => e.type)


    if (lotesFil.length) {

      db.guardarmodificarInventarioDici({ lotes: lotesFil }).then(res => {
        if (typeof res.data === "string") {
          notificar(res.data, false);
        }
        if (res.data.estado) {
          buscarInventario()
        }
      })
    }
  }
  const sendTareaRemoverDuplicado = () => {
    if (confirm("Confirme")) {
      db.sendTareaRemoverDuplicado({
        listselectEliminarDuplicados
      })
      .then(res=>{
        if (res.data.estado) {
          setlistselectEliminarDuplicados([])
        }
        notificar(res)
      })
    }
  }

  const getTareasPendientes = () => {
    db.getTareasPendientes({
    qTareaPendienteFecha,
    qTareaPendienteSucursal,
    qTareaPendienteEstado,
    qTareaPendienteNum,
    }).then(res=>{
      settareasPendientesData(res.data)
    })
  }

  const selectEliminarDuplicados = (id,idinsucursal) => {

    if (listselectEliminarDuplicados.filter(ee=>ee.id==id).length) {
      if (listselectEliminarDuplicados.filter((ee,ii)=> ii==0 && ee.id==id).length) {
        setlistselectEliminarDuplicados([])
      }
  
      if (listselectEliminarDuplicados.filter((ee,ii)=> ii!=0 && ee.id==id ).length) {
        setlistselectEliminarDuplicados(listselectEliminarDuplicados.filter(ee=>ee.id!=id))
      }
    }else{
      let add = [
        {id,idinsucursal}
      ]
      if (!listselectEliminarDuplicados[0]) {
        setlistselectEliminarDuplicados(add)
      }else{
        setlistselectEliminarDuplicados(listselectEliminarDuplicados.concat(add))
      }

    }
  }
 ///// END TAREAS PEN


  const getBarrasCargaItems = id => {

    let obj = cloneDeep(productosInventario)
    setLoading(true)
    db.getBarrasCargaItems({
      codigo_proveedor: obj[id]["codigo_proveedor"],
    })
    .then(res=>{
      setLoading(false)
      if (res.data) {
        if (res.data.estado) {

          let data = res.data.data
          obj[id]["codigo_barras_antes"] = obj[id]["codigo_barras"]
          obj[id]["codigo_barras"] = data["codigo_barras"]

          obj[id]["descripcion_antes"] = obj[id]["descripcion"]
          obj[id]["descripcion"] = data["descripcion"]
          setProductosInventario(obj)
        }
      }
    })



  }
  const getEstaInventario = () => {

    if (time != 0) {
      clearTimeout(typingTimeout)
    }

    let time = window.setTimeout(() => {
      setLoading(true)
      db.getEstaInventario({
        fechaQEstaInve,
        fechaFromEstaInve,
        fechaToEstaInve,
        orderByEstaInv,
        orderByColumEstaInv
      })
        .then(e => {
          setdataEstaInven(e.data)
          setLoading(false)
        })
    }, 150)
    setTypingTimeout(time)

  }
  const setporcenganancia = (tipo, base = 0, fun = null) => {
    let insert = window.prompt("Porcentaje")
    if (insert) {
      if (number(insert)) {
        if (tipo == "unique") {
          let re = Math.round(parseFloat(inpInvbase) + (parseFloat(inpInvbase) * (parseFloat(insert) / 100)))
          if (re) {
            setinpInvventa(re)

          }
        } else if ("list") {
          let re = Math.round(parseFloat(base) + (parseFloat(base) * (parseFloat(insert) / 100)))
          if (re) {
            fun(re)

          }
        }
      }

    }

  }

  const focusInputSibli = (tar, mov) => {
    let inputs = [].slice.call(refsInpInvList.current.elements)
    let index;
    if (tar.tagName == "INPUT") {

      if (mov == "down") {
        mov = 11
      } else if (mov == "up") {
        mov = -11
      }
    }
    for (let i in inputs) {
      if (tar == inputs[i]) {
        index = parseInt(i) + mov
        if (refsInpInvList.current[index]) {
          refsInpInvList.current[index].focus()
        }
        break
      }
    }
    if (typeof (index) === "undefined") {
      if (refsInpInvList.current[0]) {
        refsInpInvList.current[0].focus()
      }
    }
  }
  const addNewLote = e => {
    let addObj = {
      lote: "",
      creacion: "",
      vence: "",
      cantidad: "",
      type: "new",
      id: null,
    }
    setinpInvLotes(inpInvLotes.concat(addObj))
  }

  const [selectcampobusquedaestadistica,setselectcampobusquedaestadistica] = useState("")
  const [selectvalorcampobusquedaestadistica,setselectvalorcampobusquedaestadistica] = useState("")
  const [selectsucursalbusquedaestadistica,setselectsucursalbusquedaestadistica] = useState("")
  
  const dataCamposBusquedaEstadisticas =[
    {id:"", codigo:"-"},
    {id:"n1", codigo:"NOMBRE 1"},
    {id:"n2", codigo:"NOMBRE 2"},
    {id:"n3", codigo:"NOMBRE 3"},
    {id:"n4", codigo:"NOMBRE 4"},
    {id:"n5", codigo:"NOMBRE 5"},
    {id:"id_marca", codigo:"MARCA"},
    {id:"id_proveedor", codigo:"PROVEEDOR"},
    {id:"id_categoria", codigo:"CATEGORIA"},
    {id:"id_catgeneral", codigo:"SUBCATEGORIA"},
    {id:"codigo_barras", codigo:"BARRAS"},
    {id:"codigo_proveedor", codigo:"ALTERNO"},
    {id:"descripcion", codigo:"descripcion"},
  ]
  const [camposAgregadosBusquedaEstadisticas,setcamposAgregadosBusquedaEstadisticas] = useState([
    
  ])
  const [sucursalesAgregadasBusquedaEstadisticas,setsucursalesAgregadasBusquedaEstadisticas] = useState([])

  const agregarCampoBusquedaEstadisticas = () => {
    if (!camposAgregadosBusquedaEstadisticas.filter(e=>e.campo==selectcampobusquedaestadistica).length) {
      setcamposAgregadosBusquedaEstadisticas(camposAgregadosBusquedaEstadisticas.concat({
        campo:selectcampobusquedaestadistica,
        valor:selectvalorcampobusquedaestadistica,
      }))
    }
  }
  const agregarSucursalBusquedaEstadisticas = () => {
    if (!sucursalesAgregadasBusquedaEstadisticas.filter(e=>e.campo==selectcampobusquedaestadistica).length) {
      setsucursalesAgregadasBusquedaEstadisticas(sucursalesAgregadasBusquedaEstadisticas.concat({
        sucursal:selectsucursalbusquedaestadistica,
        codigo:selectsucursalbusquedaestadistica,
      }))
    }
  }
  
  
  
  
  
  const [qnombres,setqnombres] = useState("")
  const [qtiponombres,setqtiponombres] = useState("n1")
  const [datanombres,setdatanombres] = useState([])
  
  const buscarNombres = () => {
    db.buscarNombres({
      qnombres,
      qtiponombres,
    }).then(res=>{
      setdatanombres(res.data)
    })
  }

  const modNombres = (id,tiponombre,type) => {
    let newvalue ;
    if (type=="editar") {
      newvalue = window.prompt("Nuevo nombre")
    }
    db.modNombres({
      id,tiponombre,type,newvalue
    }).then(res=>{
      buscarNombres()
    })
  }

  const newNombres = (id,tiponombre,type) => {
    let newvalue ;
    newvalue = window.prompt("Nuevo nombre")
    db.newNombres({
      id,tiponombre,type,newvalue
    }).then(res=>{
      buscarNombres()
    })
  }

  
  

  const sameCatValue = (val,name)=>{
    if (confirm("¿Confirma Generalizar categoría?")) {
      let obj = cloneDeep(productosInventario);
      obj.map((e) => {
          if (e.type) {
              e[name] = val;
          }
          return e;
      });
      setProductosInventario(obj);
    }
  }
  
  const buscarInventario = (e=null,id_sucursalForce=false) => {
    setLoading(true)
    if (time != 0) {
        clearTimeout(typingTimeout)
      }
      let time = window.setTimeout(() => {
        db.getinventario({
          num: Invnum,
          itemCero: true,
          qProductosMain: qBuscarInventario,
          orderColumn: InvorderColumn,
          orderBy: InvorderBy,
          qBuscarInventarioSucursal: (!id_sucursalForce?qBuscarInventarioSucursal:id_sucursalForce),


        }).then(res => {
          setProductosInventario(res.data)
          setLoading(false)
          setIndexSelectInventario(null)
          if (res.data.length === 1) {
            setIndexSelectInventario(0)
          } else if (res.data.length == 0) {
            setinpInvbarras(qBuscarInventario)
          }
        })
      }, 150)
      setTypingTimeout(time)
  }

  


  

  const getFallas = () => {
    setLoading(true)
    db.getFallas({ qFallas, orderCatFallas, orderSubCatFallas, ascdescFallas }).then(res => {
      setfallas(res.data)
      setLoading(false)
    })
  }
  const setFalla = e => {
    let id_producto = e.currentTarget.attributes["data-id"].value
    db.setFalla({ id: null, id_producto }).then(res => {
      notificar(res)
      setSelectItem(null)

    })
  }
  const procesarImportPedidoCentral = () => {
    // console.log(valbodypedidocentral)
    // Id pedido 4
    // Count items pedido 4
    // sucursal code *


    // console.log(valheaderpedidocentral)
    //id_pedido 4 (0)
    //id_producto 4 (0)
    //base 6 (2)
    //venta 6 (2)
    //cantidad 5 (1)

    try {

      // Header...
      let id_pedido_header = valheaderpedidocentral.substring(0, 4).replace(/\b0*/g, '')
      let count = valheaderpedidocentral.substring(4, 8).replace(/\b0*/g, '')
      let sucursal_code = valheaderpedidocentral.substring(8)

      let import_pedido = {}

      if (id_pedido_header && count && sucursal_code) {

        db.getSucursal({}).then(res => {
          try {
            if (res.data) {
              if (res.data.codigo) {
                if (res.data.codigo != sucursal_code) {
                  throw ("Error: Pedido no pertenece a esta sucursal!")
                } else {
                  import_pedido.created_at = today
                  import_pedido.sucursal = sucursal_code
                  import_pedido.id = id_pedido_header
                  import_pedido.base = 0
                  import_pedido.venta = 0
                  import_pedido.items = []

                  let body = valbodypedidocentral.toString().replace(/[^0-9]/g, "")
                  if (!body) {

                    throw ("Error: Cuerpo incorrecto!")
                  } else {

                    let ids_productos = body.match(/.{1,25}/g).map((e, i) => {

                      if (e.length != 25) {
                        throw ("Error: Líneas no tienen la longitud!")

                      }
                      let id_pedido = e.substring(0, 4).replace(/\b0*/g, '')
                      let id_producto = e.substring(4, 8).replace(/\b0*/g, '')

                      let base = e.substring(8, 12).replace(/\b0*/g, '') + "." + e.substring(12, 14)
                      let venta = e.substring(14, 18).replace(/\b0*/g, '') + "." + e.substring(18, 20)

                      let cantidad = e.substring(20, 24).replace(/\b0*/g, '') + "." + e.substring(24, 25)

                      // if (id_pedido_header!=id_pedido) {
                      //   
                      //   throw("Error: Producto #"+(i+1)+" no pertenece a este pedido!")
                      // }



                      return {
                        id_producto,
                        id_pedido,
                        base,
                        venta,
                        cantidad
                      }
                    })
                    db.getProductosSerial({ count, ids_productos: ids_productos.map(e => e.id_producto) })
                      .then(res => {
                        try {

                          let obj = res.data

                          if (obj.estado) {
                            if (obj.msj) {
                              let pro = obj.msj.map((e, i) => {
                                let filter = ids_productos.filter(ee => ee.id_producto == e.id)[0];

                                let cantidad = filter.cantidad
                                let base = filter.base
                                let venta = filter.venta
                                let monto = cantidad * venta

                                import_pedido.items.push({
                                  cantidad: cantidad,
                                  producto: {
                                    precio_base: base,
                                    precio: venta,
                                    codigo_barras: e.codigo_barras,
                                    codigo_proveedor: e.codigo_proveedor,
                                    descripcion: e.descripcion,
                                    id: e.id,
                                  },
                                  id: i,
                                  monto,
                                })

                                import_pedido.base += parseFloat(cantidad * base)
                                import_pedido.venta += parseFloat(monto)


                              })
                              // console.log("import_pedido",import_pedido)
                              setpedidoCentral(pedidosCentral.concat(import_pedido))
                              setshowaddpedidocentral(false)

                            }
                          } else {
                            alert(obj.msj)
                          }

                        } catch (err) {
                          alert(err)
                        }

                      })

                  }

                }
              }
            }
          } catch (err) {
            alert(err)
          }
        })

      } else {
        throw ("Error: Cabezera incorrecta!")
      }
    } catch (err) {
      alert(err)
    }
  }

  const verDetallesFactura = (e = null) => {
    let id = facturas[factSelectIndex]
    if (e) {
      id = e
    }
    if (id) {
      db.openVerFactura({ id: facturas[factSelectIndex].id })
    }

  }
  /////CLOSE VENTAS IMPORT






  useEffect(() => {
    getVentas()
  }, [selectfechaventa])

 


  useEffect(() => {
    getFacturas()
  }, [
    factqBuscar,
    factqBuscarDate,
    factOrderBy,
    factOrderDescAsc
  ])





  useEffect(() => {
    setInputsProveedores()
  }, [indexSelectProveedores])



  function formatAmountNoDecimals( number ) {
    var rgx = /(\d+)(\d{3})/;
    while( rgx.test( number ) ) {
        number = number.replace( rgx, '$1' + '.' + '$2' );
    }
    return number;
  }
  const removeMoneda = val => {
    return number(val.replace("Bs/$ ","").replace("Bs.","").replace("Bs. ","").replace("$","").replace(" ","").replaceAll(/\./g,"").replace(",","."))
  }

function formatAmount( number, simbol ) {

    // remove all the characters except the numeric values
    number = number.replace( /[^0-9]/g, '' );

    // set the default value
    if( number.length == 0 ) number = "0.00";
    else if( number.length == 1 ) number = "0.0" + number;
    else if( number.length == 2 ) number = "0." + number;
    else number = number.substring( 0, number.length - 2 ) + '.' + number.substring( number.length - 2, number.length );

    // set the precision
    number = new Number( number );
    number = number.toFixed( 2 );    // only works with the "."

    // change the splitter to ","
    number = number.replace( /\./g, ',' );

    // format the amount
    let x = number.split( ',' );
    let x1 = x[0];
    let x2 = x.length > 1 ? ',' + x[1] : '';

    return simbol+formatAmountNoDecimals( x1 ) + x2;
}

  function dateFormat(input_D, format_D) {
    // input date parsed
    /* const date = new Date(input_D);

    //extracting parts of date string
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();    

    //to replace month
    format_D = format_D.replace("MM", month.toString().padStart(2,"0"));        

    //to replace year
    if (format_D.indexOf("yyyy") > -1) {
        format_D = format_D.replace("yyyy", year.toString());
    } else if (format_D.indexOf("yy") > -1) {
        format_D = format_D.replace("yy", year.toString().substr(2,2));
    }

    //to replace day
    format_D = format_D.replace("dd", day.toString().padStart(2,"0")); */

    return input_D;
  }


  const moneda = (value, decimals = 2, separators = ['.', ".", ',']) => {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
      return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
      separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
      result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
        + separators[idx] + result;
      idx = (++idx) % 2;
      start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
  }
  const number = (val) => {
    if (!val) return ""
    if (val == "") return ""
    return val.toString().replace(/[^\d|\.]+/g, '')
  }
  const loginRes = res => {
    notificar(res)
    if (res.data) {
      setLoginActive(res.data.estado)
      setuser({
        id_usuario: res.data.id_usuario,
        tipo_usuario: res.data.tipo_usuario,
        usuario: res.data.usuario,
        nombre: res.data.nombre,
      }) 
    }
  }

  const notificar = (msj, fixed = true, simple = false) => {
    if (fixed) {
      setTimeout(() => {
        setMsj("")
      }, 3000)
    } else {
      setTimeout(() => {
        setMsj("")
      }, 30000)
    }
    if (msj == "") {
      setMsj("")
    } else {
      if (msj.data) {
        if (msj.data.msj) {
          setMsj(msj.data.msj)

        } else {

          setMsj(JSON.stringify(msj.data))
        }
      } else if (typeof msj === 'string' || msj instanceof String) {
        setMsj(msj)
      }

    }
  }


  ///////////Inventario


  const getVentas = () => {
    setLoading(true)

    if (sucursales.filter(e => e.char == sucursalSelect).length) {
      db.getVentas({ selectfechaventa, id_sucursal: sucursales.filter(e => e.char == sucursalSelect)[0].id }).then(res => {
        setventas(res.data)
        setLoading(false)
      })

    }
  }

  const showCantidadCarritoFun = () => {
    showCantidadCarrito("carrito")
  }

  const selectView = view_prop => {
    if ((view_prop == view) && (sucursalSelect !== null) && (sucursalSelect !== "inventario")) {
      return true
    }
    return false
  }
  const setCarrito = e => {
    let tipo = e.currentTarget.attributes["data-tipo"].value

    let id_producto = null
    if (indexSelectCarrito !== null && productosInventario) {
      if (productosInventario[indexSelectCarrito]) {
        id_producto = productosInventario[indexSelectCarrito].id
      }
    }
    setLoading(true)
    db.setCarrito({ ctSucursales, id_producto }).then(res => {

      buscarInventario()
      getPedidos()
      getPedidosList()
      notificar(res)
      setctSucursales([])
      setLoading(false)
      setshowCantidadCarrito(tipo)
    })
  }

  const setProdCarritoInterno = () => {

    let id_producto = null
    if (indexSelectCarrito !== null && productosInventario) {
      if (productosInventario[indexSelectCarrito]) {
        id_producto = productosInventario[indexSelectCarrito].id
      }
    }
    setLoading(true)
    let ct = window.prompt("¿Cantidad?")
    if (ct) {
      if (number(ct)) {
        db.setCarrito({
          ctSucursales: [{
            id: pedidoData.id_sucursal,
            val: number(ct),
            id_pedido: pedidoData.id
          }], id_producto
        }).then(res => {

          selectPedido()
          notificar(res)
          setLoading(false)
          setshowCantidadCarrito("pedidoSelect")
        })
      }

    }
  }
  const getPedidosList = () => {
    db.getPedidosList().then(res => {
      setpedidoList(res.data)


      if (res.data) {
        let ctSucursales_copy = []
        res.data.map(e => {
          if (!ctSucursales_copy.filter(ee => ee.id == e.id_sucursal).length) {
            ctSucursales_copy.push({ id: e.id_sucursal, val: "", id_pedido: e.id })
          }
        })
        setctSucursales(ctSucursales_copy)
      }
    })
  }

//// NOVEDADES PEDIDOS
const [qnovedadesPedidodos,setqnovedadesPedidodos] = useState("")
const [novedadesPedidosData,setnovedadesPedidosData] = useState([])

const getNovedadesPedidosData = () => {
  db.getNovedadesPedidosData({qnovedadesPedidodos})
  .then(res=>{
    setnovedadesPedidosData(res.data)
  })
}


////
  const eliminarVinculoCentral = (id) => {
    if (confirm("CONFIRME")) {
      
      db.removeVinculoCentral({
        id
      })
      .then(res=>{
       /*  if (res.data.estado) {
          setpedidoData(res.data.pedido)
        } */
        notificar(res)
      })
    }
  }


  const revolverNovedadItemTrans = (iditem,type,accion) => {
    db.revolverNovedadItemTrans({
      iditem,
      type,
      accion,
    })
    .then(res=>{
      if (res.data.estado) {
        setpedidoData(res.data.pedido)
      }
      notificar(res)
    })
  }
  const getPedidos = e => {
    setLoading(true)
    db.getPedidos({ 
      
      qpedido, 
      qpedidoDateFrom, 
      qpedidoDateTo, 
      qpedidoOrderBy, 
      qpedidoOrderByDescAsc, 
      qestadopedido,
      qpedidosucursal,
      qpedidosucursaldestino,
     }).then(res => {
      setpedidos(res.data)
      setLoading(false)
    })
  }
  const delPedido = () => {
    if (confirm("¿Seguro de eliminar?")) {
      let id;

      if (pedidoData.id) {
        id = pedidoData.id
      }
      if (id) {
        db.delPedido({ id }).then(res => {
          notificar(res)
          if (res.data.estado) {
            setpedidoData(null)
            setshowCantidadCarrito("procesar")
          }
          getPedidos()
          getPedidosList()
        })

      }
    }
  }
  const selectPedido = id => {
    let fil = pedidos.filter(e=>e.id==id)
    if (fil.length) {
      setpedidoData(fil[0])
      setshowCantidadCarrito("pedidoSelect")
    }
  }

  const setCtCarrito = e => {
    let cantidad = window.prompt("Nueva cantidad")

    if (number(cantidad)) {
      setLoading(true)
      let id = e.currentTarget.attributes["data-id"].value
      db.setCtCarrito({ id, cantidad }).then(res => {
        setLoading(false)
        selectPedido()
      })

    }
  }

  const setDelCarrito = e => {
    if (confirm("¿Desea Eliminar?")) {

      setLoading(true)
      let id = e.currentTarget.attributes["data-id"].value
      db.setDelCarrito({ id }).then(res => {
        setLoading(false)
        selectPedido()
      })
    }
  }


  const aprobarRevisionPedido = (estado) => {
    if (pedidoData) {
      if (pedidoData.id) {
        if (confirm("Confirme")) {
          setLoading(true)
          db.aprobarRevisionPedido({ id: pedidoData.id, estado }).then(res => {
            notificar(res)
            if (res.data) {
              if (res.data.estado) {
                getPedidos()
                setshowCantidadCarrito("procesar")
              }
            }
            setLoading(false)
          })
        }
      }
    }

  }
  const sendPedidoSucursal = () => {
    if (pedidoData) {
      if (pedidoData.id) {
        if (confirm("¿Realmente desea enviar el pedido " + pedidoData.id + " a " + pedidoData.sucursal.nombre)) {
          setLoading(true)
          db.sendPedidoSucursal({ id: pedidoData.id }).then(res => {
            notificar(res)
            getPedidos()
            setshowCantidadCarrito("procesar")
            setLoading(false)
          })
        }
      }
    }
  }

  const showPedidoBarras = () => {
    window.open("showPedidoBarras?id=" + pedidoData.id, "targed=blank")
  }

  //////EnD last////


  ///////////////////////////////Panel//////////////////////////////////////////77  
  ///////////////////////////////Panel//////////////////////////////////////////77  
  ///////////////////////////////Panel//////////////////////////////////////////77  
  ///////////////////////////////Panel//////////////////////////////////////////77  
  ///////////////////////////////Panel//////////////////////////////////////////77  
  ///////////////////////////////Panel//////////////////////////////////////////77  
  ///////////////////////////////Panel//////////////////////////////////////////77  

  const [viewmainPanel, setviewmainPanel] = useState("panelgeneral")

  const [subViewInventario, setsubViewInventario] = useState("gestion")

  const [msj, setMsj] = useState("")
  const [loading, setLoading] = useState(false)
  const [loginActive, setLoginActive] = useState(false)
  const [sucursalSelect, setsucursalSelect] = useState(null)

  const [sucursales, setsucursales] = useState([])
  const [subviewpanelsucursales, setsubviewpanelsucursales] = useState("cierres")
  const [cuentasporpagarDetallesView, setcuentasporpagarDetallesView] = useState("cuentas")
  
  


  const [sucursalListData, setsucursalListData] = useState([])
  const [sucursalDetallesData, setsucursalDetallesData] = useState({})

  const [fechasMain1, setfechasMain1] = useState("")
  const [fechasMain2, setfechasMain2] = useState("")
  const [filtros, setfiltros] = useState({})
  const [qestatusaprobaciocaja, setqestatusaprobaciocaja] = useState(0)
  const [qfiltroaprotransf,setqfiltroaprotransf] = useState("")
  const [bancoqfiltroaprotransf,setbancoqfiltroaprotransf] = useState("")
  const [qcuentasPorPagar, setqcuentasPorPagar] = useState("")

  const [cuentasPagosMetodoDestino,setcuentasPagosMetodoDestino] = useState("")
  const [cuentasPagosDescripcion, setcuentasPagosDescripcion] = useState("")
  const [cuentasPagosMonto, setcuentasPagosMonto] = useState("")
  const [cuentasPagosPuntooTranfe, setcuentasPagosPuntooTranfe] = useState("Transferencia")
  const [cuentasPagosSucursal, setcuentasPagosSucursal] = useState("")
  const [cuentasPagosMetodo, setcuentasPagosMetodo] = useState("")
  const [cuentasPagosFecha, setcuentasPagosFecha] = useState("")
  
  const [cuentasPagoTipo, setcuentasPagosTipo] = useState("egreso")
  const [cuentasPagosCategoria, setcuentasPagosCategoria] = useState("")

  

  const [subViewNomina, setsubViewNomina] = useState("gestion")
  const [subViewNominaGestion, setsubViewNominaGestion] = useState("personal")

  const [nominaNombre, setnominaNombre] = useState("")
  const [nominaCedula, setnominaCedula] = useState("")
  const [nominaTelefono, setnominaTelefono] = useState("")
  const [nominaDireccion, setnominaDireccion] = useState("")
  const [nominaFechadeNacimiento, setnominaFechadeNacimiento] = useState("")
  const [nominaactivo,setnominaactivo] = useState("")
  const [nominaFechadeIngreso, setnominaFechadeIngreso] = useState("")
  const [nominaGradoInstruccion, setnominaGradoInstruccion] = useState("")
  const [nominaCargo, setnominaCargo] = useState("")
  const [nominaSucursal, setnominaSucursal] = useState("")
  const [nominaid_sucursal_disponible, setnominaid_sucursal_disponible] = useState("")
	const [shownewpersonal, setshownewpersonal] = useState(false)
  const [qSucursalNominaFecha,setqSucursalNominaFecha] = useState("")
  

  const [indexSelectNomina, setIndexSelectNomina] = useState(null)
  const [qNomina, setqNomina] = useState("")
  const [qSucursalNomina, setqSucursalNomina] = useState("")
  const [qCargoNomina, setqCargoNomina] = useState("")

  const [qSucursalNominaOrden,setqSucursalNominaOrden] = useState("desc")
  const [qSucursalNominaOrdenCampo,setqSucursalNominaOrdenCampo] = useState("")
  const [qSucursalNominaEstatus,setqSucursalNominaEstatus] = useState("1")

  const [nominaData, setnominaData] = useState([])

  const [nominapagodetalles, setnominapagodetalles] = useState({})
  const [cargosDescripcion, setcargosDescripcion] = useState("")
  const [cargosSueldo, setcargosSueldo] = useState("")
  const [qCargos, setqCargos] = useState("")
  const [indexSelectCargo, setindexSelectCargo] = useState(null)

  const [cargosData, setcargosData] = useState([])

  const [usuariosData, setusuariosData] = useState([]);
  const [usuarioNombre, setusuarioNombre] = useState("");
  const [usuarioUsuario, setusuarioUsuario] = useState("");
  const [usuarioRole, setusuarioRole] = useState("");
  const [usuarioClave, setusuarioClave] = useState("");
  const [usuarioArea, setusuarioArea] = useState("");
  const [usuarioId_sucursal, setusuarioId_sucursal] = useState("");
  


  const [qBuscarUsuario, setQBuscarUsuario] = useState("");
  const [indexSelectUsuarios, setIndexSelectUsuarios] = useState(null);

  const [filtronominaq, setfiltronominaq] = useState("")
  const [filtronominacargo, setfiltronominacargo] = useState("")

  /* const categoriasCajas = [
    {id:1, descripcion: "INGRESO DE SUCURSAL"},
    {id:2, descripcion: "PAGO PROVEEDOR"},
    {id:3, descripcion: "INVERSION"},
    {id:4, descripcion: "GASTOS"},
    {id:5, descripcion: "COMPRAS CONTADO"},
    {id:6, descripcion: "TRASPASO ENTRE CUENTA"},
    {id:7, descripcion: "COMISIÓN"},
  ] */

  useEffect(() => {
    getToday()
    getSucursales()
    getProveedores()
    getMetodosPago()
    getCatCajas()

    getMarcas()
    getCatGenerals()
    getCategorias()
    getDatinputSelectVinculacion()


  }, [])


  useEffect(() => {
    setInputsUsuarios();
  }, [indexSelectUsuarios]);

  const getToday = () => {
    db.today({}).then(res => {
      let today = res.data
      /* setfechaGastos(today)
      setselectfechaventa(today)
      setfactqBuscarDate(today)
      
      setqpedidoDateTo(today)
      setqpedidoDateFrom(today) */
      setfechasMain1(today)
      setfechasMain2(today)
      setcuentasPagosFecha(today)
      setfechaSelectAuditoria(today)
      setfechaHastaSelectAuditoria(today)
      setinpfechaLiquidar(today)
      setinpfechaLiquidar(today)
      setgastosFecha(today)
      setgastosQFecha(today)
      setgastosQFechaHasta(today)
      setcontrolefecQDesde(today)
      setcontrolefecQHasta(today)
      setcontrolbancoQDesde(today)
      setcontrolbancoQHasta(today)
      setqSucursalNominaFecha(today)

      setqfechadesdeAprobaFlujCaja(today)
      setqfechahastaAprobaFlujCaja(today)
      
      setqdesdePedidoAnulacionAprobacion(today)
      setqhastaPedidoAnulacionAprobacion(today)
      


      
      
    })
  }
  const getSucursales = (q="",callback=null) => {
    db.getSucursales({
      q
    }).then(res => {
      res.data = res.data.map(e=>{
        e.color =  "#"+colorFun(1575*e.id+(e.codigo).slice(0,6))
        return e
      })
      setsucursales(res.data)
      let col = {}
      res.data.map(e=>{
        col[e.codigo] =  "#"+colorFun(1575*e.id+(e.codigo).slice(0,6))
      })
      setcolorSucursalData(col)

      if (callback!==null) {
        callback(res.data)
      }
    })
  }
  const getsucursalListData = () => {
    db.getsucursalListData({
      fechasMain1,
      fechasMain2,
      filtros,

      subviewpanelsucursales,
    }).then(res => {
      setsucursalListData(res.data)
    })
  }
  const aprobarMovCajaFuerte = (id,tipo) => {
    if (confirm("Confirma ("+tipo+")")) {
      db.aprobarMovCajaFuerte({tipo,id}).then(res=>{
        notificar(res.data)
        getAprobacionFlujoCaja()
      })
    }
  }

  const [dataAprobacionFlujoCaja, setdataAprobacionFlujoCaja] = useState([])

  const [qfechadesdeAprobaFlujCaja, setqfechadesdeAprobaFlujCaja] = useState("")
  const [qfechahastaAprobaFlujCaja, setqfechahastaAprobaFlujCaja] = useState("")
  const [qAprobaFlujCaja, setqAprobaFlujCaja] = useState("")
  const [qCategoriaAprobaFlujCaja, setqCategoriaAprobaFlujCaja] = useState("")
  const [qSucursalAprobaFlujCaja, setqSucursalAprobaFlujCaja] = useState("")
  


  const getAprobacionFlujoCaja = () => {
    db.getAprobacionFlujoCaja({
      qestatusaprobaciocaja,
      qfechadesdeAprobaFlujCaja,
      qfechahastaAprobaFlujCaja,
      qAprobaFlujCaja,
      qCategoriaAprobaFlujCaja,
      qSucursalAprobaFlujCaja,
    }).then(res=>{
      setdataAprobacionFlujoCaja(res.data)
    })
  } 
  const aprobarCreditoFun = (id,tipo) => {
    if (confirm("Confirma ("+tipo+")")) {
      db.aprobarCreditoFun({tipo,id}).then(res=>{
        notificar(res.data)
        getsucursalDetallesData() 
      })
    }
  }

  const aprobarTransferenciaFun = (id,tipo) => {
    if (confirm("Confirma ("+tipo+")")) {
      db.aprobarTransferenciaFun({tipo,id}).then(res=>{
        notificar(res.data)
        getsucursalDetallesData() 
      })
    }
  }

  const [modalmovilx, setmodalmovilx] = useState(0);
  const [modalmovily, setmodalmovily] = useState(0);
  const [modalmovilshow, setmodalmovilshow] = useState(false);
  const [idselectproductoinsucursalforvicular,setidselectproductoinsucursalforvicular] = useState({ index: null, id_producto_central: null });

  const inputbuscarcentralforvincular = useRef(null);
  const modalmovilRef = useRef(null)
  const openVincularSucursalwithCentral = (e, id_producto_central) => {
      //setmodalmovilshow(true);
      /* console.log(idinsucursal,"idinsucursal")
      console.log(e,"idinsucursal e") */
      if (
        id_producto_central.index == idselectproductoinsucursalforvicular.index &&
          modalmovilshow
      ) {
          setmodalmovilshow(false);
      } else {
          setmodalmovilshow(true);

          if (modalmovilRef) {
              if (modalmovilRef.current) {
                  modalmovilRef.current?.scrollIntoView({ block: "nearest", behavior: 'smooth' });
              }
          }
      }


      let p = e.currentTarget.getBoundingClientRect();
      let y = p.top + window.scrollY;
      let x = p.left;
      setmodalmovily(y);
      setmodalmovilx(x);

      setidselectproductoinsucursalforvicular(id_producto_central);
  };

  const linkproductocentralsucursal = (idinsucursal,id_sucursal) => {
      //if (!inventarioSucursalFromCentral.filter(e => e.id_vinculacion == idinsucursal).length) {
          /* changeInventarioFromSucursalCentral(
              idinsucursal,
              idselectproductoinsucursalforvicular.index,
              idselectproductoinsucursalforvicular.id,
              "changeInput",
              "id_vinculacion"
          ); */

          if (confirm("Confirme VINCULO")) {
            
            db.sendVinculoCentralToSucursal({
              idinsucursal,
              id_sucursal,
              id_producto_central: idselectproductoinsucursalforvicular.id_producto_central,
            })
            .then(res=>{
                notificar(res)
                if (res.data.estado) {
                  selectCuentaPorPagarProveedorDetallesFun()
                  
                }
            })
  
            //setmodalmovilshow(false);
          }
     /*  } else {
          alert("¡Error: Éste ID ya se ha vinculado!")
      } */
  };


  const [subviewAuditoria, setsubviewAuditoria] = useState("cuadre") 
  const [subviewAuditoriaGeneral, setsubviewAuditoriaGeneral] = useState("") //efectivo banco transferencias 


  const [selectCuentaPorPagarId, setSelectCuentaPorPagarId] = useState(null)
  const [qcuentasPorPagarDetalles, setqcuentasPorPagarDetalles] = useState("")
  const [qcampoBusquedacuentasPorPagarDetalles,setqcampoBusquedacuentasPorPagarDetalles] = useState("numfact")
  const [qinvertircuentasPorPagarDetalles,setqinvertircuentasPorPagarDetalles] = useState(0)

  
  
  
  const [qcuentasPorPagarTipoFact, setqcuentasPorPagarTipoFact] = useState("")
  const [qCampocuentasPorPagarDetalles, setqCampocuentasPorPagarDetalles] = useState("updated_at")
  const [numcuentasPorPagarDetalles, setnumcuentasPorPagarDetalles] = useState("20")
  
  const [OrdercuentasPorPagarDetalles, setOrdercuentasPorPagarDetalles] = useState("desc")
  const [qFechaCampocuentasPorPagarDetalles, setqFechaCampocuentasPorPagarDetalles] = useState("")
  const [fechacuentasPorPagarDetalles, setfechacuentasPorPagarDetalles] = useState("")
  const [categoriacuentasPorPagarDetalles, setcategoriacuentasPorPagarDetalles] = useState("")
  const [tipocuentasPorPagarDetalles, settipocuentasPorPagarDetalles] = useState("")
  const [OrderFechacuentasPorPagarDetalles,setOrderFechacuentasPorPagarDetalles] = useState("desc")
  const [SelectCuentaPorPagarDetalle,setSelectCuentaPorPagarDetalle] = useState(null)
  const [selectFactPagoArr,setselectFactPagoArr] = useState([])
  const [subviewAgregarFactPago,setsubviewAgregarFactPago] = useState("pago")
  const [sucursalcuentasPorPagarDetalles,setsucursalcuentasPorPagarDetalles] = useState("")
  const [selectFactPagoid,setselectFactPagoid] = useState(null)
  const [selectFactPagoid_sucursal,setselectFactPagoid_sucursal] = useState(null)

  const [montobs1PagoFact,setmontobs1PagoFact] = useState("")
  const [tasabs1PagoFact,settasabs1PagoFact] = useState("")
  const [metodobs1PagoFact,setmetodobs1PagoFact] = useState("")

  const [montobs2PagoFact,setmontobs2PagoFact] = useState("")
  const [tasabs2PagoFact,settasabs2PagoFact] = useState("")
  const [metodobs2PagoFact,setmetodobs2PagoFact] = useState("")

  const [montobs3PagoFact,setmontobs3PagoFact] = useState("")
  const [tasabs3PagoFact,settasabs3PagoFact] = useState("")
  const [metodobs3PagoFact,setmetodobs3PagoFact] = useState("")

  const [montobs4PagoFact,setmontobs4PagoFact] = useState("")
  const [tasabs4PagoFact,settasabs4PagoFact] = useState("")
  const [metodobs4PagoFact,setmetodobs4PagoFact] = useState("")

  const [montobs5PagoFact,setmontobs5PagoFact] = useState("")
  const [tasabs5PagoFact,settasabs5PagoFact] = useState("")
  const [metodobs5PagoFact,setmetodobs5PagoFact] = useState("")

  const [refbs1PagoFact,setrefbs1PagoFact] = useState("")
  const [refbs2PagoFact,setrefbs2PagoFact] = useState("")
  const [refbs3PagoFact,setrefbs3PagoFact] = useState("")
  const [refbs4PagoFact,setrefbs4PagoFact] = useState("")
  const [refbs5PagoFact,setrefbs5PagoFact] = useState("")

  
  const [newfactid_proveedor, setnewfactid_proveedor] = useState("")
  const [newfactnumfact, setnewfactnumfact] = useState("")
  const [newfactnumnota, setnewfactnumnota] = useState("")
  const [newfactdescripcion, setnewfactdescripcion] = useState("")
  const [newfactsucursal, setnewfactsucursal] = useState("")
  
  const [newfactsubtotal, setnewfactsubtotal] = useState("")
  const [newfactdescuento, setnewfactdescuento] = useState("")
  const [newfactmonto_exento, setnewfactmonto_exento] = useState("")
  const [newfactmonto_gravable, setnewfactmonto_gravable] = useState("")
  const [newfactiva, setnewfactiva] = useState("")
  const [newfactmonto, setnewfactmonto] = useState("")
  const [newfactfechaemision, setnewfactfechaemision] = useState("")
  const [newfactfechavencimiento, setnewfactfechavencimiento] = useState("")
  const [newfactfecharecepcion, setnewfactfecharecepcion] = useState("")
  const [newfactnota, setnewfactnota] = useState("")
  const [newfacttipo, setnewfacttipo] = useState("1")
  const [newfactfrecuencia, setnewfactfrecuencia] = useState("")
  const [selectFactEdit, setselectFactEdit] = useState(null)
  const [selectProveedorCxp, setselectProveedorCxp] = useState("")
  const [cuentaporpagarAprobado,setcuentaporpagarAprobado] = useState(1)
  const [efectivoDisponibleSucursalesData,setefectivoDisponibleSucursalesData] = useState([])
  const [controlefecSelectCat,setcontrolefecSelectCat] = useState("")
  const [controlefecQDescripcion,setcontrolefecQDescripcion] = useState("")
  const [dataselectFacts, setdataselectFacts] = useState({
    "sum": 0,
    "data": []
  })
  
  const [modalfilesexplorercxp,setmodalfilesexplorercxp] = useState(false)
  const [selectFilecxp,setselectFilecxp] = useState(null)

  const [dataFilescxp, setdataFilescxp] = useState([])
  const [qnumfactFilescxp, setqnumfactFilescxp] = useState("")
  const [qid_proveedorFilescxp, setqid_proveedorFilescxp] = useState("")
  const [qid_sucursalFilescxp, setqid_sucursalFilescxp] = useState("")
  const [qfechaFilescxp, setqfechaFilescxp] = useState("")
  const [inputimportitems, setinputimportitems] = useState("")
  
  
  const conciliarCuenta = id => {
    if (confirm("Confirme")) {
      db.conciliarCuenta({id}).then(res=>{
        selectCuentaPorPagarProveedorDetallesFun()


      })
    }
  }
  const seleccionarFilecxpFun = (id) => {
    setselectFilecxp(id)
    setviewmainPanel("cargarfactsdigitales")
  }

  const delFilescxp = (id) => {
    if (confirm("Confirme ELIMINACIÓN DE "+id)) {
      db.delFilescxp({id}).then(res=>{
        if (res.data.estado) {
          getFilescxp()
        }
        notificar(res)
      })
      
    }
  }
  const getFilescxp = (id) => {
    db.getFilescxp({
      qnumfactFilescxp,
      qid_proveedorFilescxp,
      qid_sucursalFilescxp,
      qfechaFilescxp,
    }).then(res=>{
      if (res.data.estado) {
        setdataFilescxp(res.data)
      }else{
        setdataFilescxp([])
      }
    })
  }
  const showFilescxp = (id) => {
    db.showFilescxp(id)
  }


  const setConciliarMovCajaMatriz = id => {
    if (confirm("Confirme")) {
      db.setConciliarMovCajaMatriz({
        id
      })
      .then(res=>{
        getControlEfec()
      })
    }
  }


 

  const [datacajamatriz, setdatacajamatriz] = useState([])
  const [qcajamatriz,setqcajamatriz] = useState("")
  const [sucursalqcajamatriz,setsucursalqcajamatriz] = useState("")
  const [fechadesdecajamatriz,setfechadesdecajamatriz] = useState("")
  const [fechahastacajamatriz,setfechahastacajamatriz] = useState("")

  const [bancodepositobanco,setbancodepositobanco] = useState("")
  const [fechadepositobanco,setfechadepositobanco] = useState("")
  const [selectdepositobanco,setselectdepositobanco] = useState("")
  
  const depositarmatrizalbanco = (id) => {
    db.depositarmatrizalbanco({
      id,
      banco: bancodepositobanco,
      fecha: fechadepositobanco,
    }).then(res=>{
      notificar(res)
      if (res.data.estado) {
        
        setbancodepositobanco("")
        setfechadepositobanco("")
        setselectdepositobanco("")
        getControlEfec()
      }

    })
  }
  const getCajaMatriz = () => {
    db.getCajaMatriz({
      qcajamatriz,
      sucursalqcajamatriz,
      fechadesdecajamatriz,
      fechahastacajamatriz,
    }).then(res=>{
      setdatacajamatriz(res.data)
    })
  }

  const getDisponibleEfectivoSucursal = () => {
    db.getDisponibleEfectivoSucursal({}).then(res=>{
      if (res.data.data.length) {
        setefectivoDisponibleSucursalesData(res.data)
      }else{
        setefectivoDisponibleSucursalesData([])
      }
    })
  }

  const [descuentoGeneralFats,setdescuentoGeneralFats] = useState("")
  const sendDescuentoGeneralFats = () => {
    db.sendDescuentoGeneralFats({
      dataselectFacts: dataselectFacts.data,
      descuentoGeneralFats,
    }).then(res=>{
      selectCuentaPorPagarProveedorDetallesFun()
      notificar(res)
    })
  }
  const saveFacturaLote = () => {
    const formData = new FormData();
    formData.append("imagen",factInpImagen);
    formData.append("selectFilecxp",selectFilecxp);
    formData.append("facturas",JSON.stringify(selectCuentaPorPagarId.detalles.filter(e=>e.type)));

    db.saveFacturaLote(formData)
    .then(res=>{
      if (res.data.estado) {
        selectCuentaPorPagarProveedorDetallesFun()
        setselectFilecxp(null)
        setfactInpImagen("")
        notificar(res)
        
      }
    })
  }
  const handleFacturaxLotes = (val, i, type, name = null) => {
    let det = cloneDeep(selectCuentaPorPagarId);
    let obj = det.detalles

    switch (type) {
      case "delModeUpdateDelete":
        delete obj[i].type;
        break;
        case "delNew":
          obj = obj.filter((e, ii) => ii !== i);
          break;
        case "changeInput":
          obj[i][name] = val;
          break;
        case "update":
            if (obj[i].type != "new") {
                if (!obj.filter(e=>e.type).length) {
                  obj[i].type = "update";
                }
            }
            break;
        case "add":
            let pro = "";
            let newObj = [
                {
                    id: null,
                    id_proveedor: "",
                    id_sucursal: "",
                    numfact: "",
                    numnota: "",
                    descripcion: "",
                    subtotal: "",
                    descuento: "",
                    monto_exento: "",
                    monto_gravable: "",
                    iva: "",
                    monto: "",
                    estatus: "",
                    montobs1: "",
                    tasabs1: "",
                    metodobs1: "",
                    refbs1: "",
                    montobs2: "",
                    tasabs2: "",
                    metodobs2: "",
                    refbs2: "",
                    montobs3: "",
                    tasabs3: "",
                    metodobs3: "",
                    refbs3: "",
                    montobs4: "",
                    tasabs4: "",
                    metodobs4: "",
                    refbs4: "",
                    montobs5: "",
                    tasabs5: "",
                    metodobs5: "",
                    refbs5: "",
                    metodo: "",
                    fechaemision: "",
                    fechavencimiento: "",
                    fecharecepcion: "",
                    nota: "",
                    aprobado: "",
                    tipo: "",
                    frecuencia: "",
                    idinsucursal: "",
                    type: "new",
                },
            ];

            if (!obj.filter(e=>e.type).length) {
              obj = newObj.concat(obj);
            }
            break;
        case "delMode":
              if (!obj.filter(e=>e.type).length) {
                
                obj[i].type = "delete";
              }
            break;
    }
    det.detalles = obj
    setSelectCuentaPorPagarId(det);
  };
  const abonarFactLote = (id_proveedor=null) => {
    setcuentasporpagarDetallesView("pagos");
    setsubviewAgregarFactPago("pago")
    
    selectCuentaPorPagarProveedorDetallesFun("buscar",id_proveedor,dataselectFacts.data.map(e=>e.id),res=>{
      let pagos = [] 
      dataselectFacts.data.map(e=>{
        let filterData = res.filter(ee=>ee.id==e.id)
        if (filterData.length) {
          pagos.push({
            id: e.id,
            val: filterData[0].balance*-1,
            valfact: e.monto,
            numfact: e.numfact,
            sucursal: filterData[0].sucursal,
            proveedor: filterData[0].proveedor,
            fechaemision: filterData[0].fechaemision,
            fechavencimiento: filterData[0].fechavencimiento,
            monto_bruto: filterData[0].monto_bruto,
            monto_descuento: filterData[0].monto_descuento,
            descuento: filterData[0].descuento,
            aprobado: filterData[0].aprobado,
            condicion: filterData[0].condicion,
            monto: filterData[0].monto,
            monto_abonado: filterData[0].monto_abonado,

            balance: filterData[0].balance,

            guardado:true,
          })
          
        }
      })
      setselectAbonoFact(pagos)

    })
  }
  const abonarFact = (id_proveedor,id) => {
    setcuentasporpagarDetallesView("pagos");
    setsubviewAgregarFactPago("pago")
    setselectProveedorCxp(id_proveedor)
    if (selectCuentaPorPagarId) {
      if (selectCuentaPorPagarId.detalles) {
        if (selectCuentaPorPagarId.detalles.length) {
          let clone = cloneDeep(selectCuentaPorPagarId)
          clone["detalles"] = clone.detalles.filter(e=>e.id==id)
          setSelectCuentaPorPagarId(clone)
        }
      }
    }
  }

  const selectFacts = (event,id,type="normal") => {
    if (selectCuentaPorPagarId.detalles) {
      if (selectCuentaPorPagarId.detalles.length) {
        let d = selectCuentaPorPagarId.detalles.filter(e=>e.id==id)

        if (d.length) {
          if (type=="normal" || (type=="leave" && dataselectFacts.data.length)) {
            let dataFilter = d[0]
            let clone = cloneDeep(dataselectFacts)
            let sum = 0
            clone.data.map(e=>{
              sum += parseFloat(e.balance)
            })
            
            if(dataselectFacts.data.filter(selefil =>selefil.id==id).length){
              sum -= parseFloat(dataFilter.balance)
              setdataselectFacts({
                data: clone.data.filter(e=>id!=e.id),
                sum:clone.data.length?sum:0,
              })
            }else{
              sum += parseFloat(dataFilter.balance)
              setdataselectFacts({
                data: clone.data.concat(dataFilter),
                sum
              })
            }
          }

        }


      }
    }
  }
  const showImageFact = (id) => {
    db.showImageFact(((id.indexOf("/")===-1)? ("facturas/"+id): id))
  }

  const delItemSelectAbonoFact = id => {
    if (confirm("Confirme")) {
      setselectAbonoFact(selectAbonoFact.filter(e=>e.id!=id))
    }
  }
  const modeEditarFact = id => {
    
    if (SelectCuentaPorPagarDetalle) {
        if (selectCuentaPorPagarId) {
            if (selectCuentaPorPagarId.detalles) {
                let f = selectCuentaPorPagarId.detalles.filter(e=>e.id==SelectCuentaPorPagarDetalle)
                if (f.length) {
                    let data = f[0]
                    setselectProveedorCxp(data.id_proveedor)
                    if (data.monto>0) {
                      setsubviewAgregarFactPago("pago")
                      setcuentasporpagarDetallesView("pagos")
                      
                      setselectFactPagoid(data.idinsucursal)
                      setselectFactPagoid_sucursal(data.id_sucursal)
                      setcuentasPagosDescripcion(data.numfact)
                      setcuentasPagosMonto(data.monto)
                      setcuentasPagosMetodo(data.metodo)
                      setcuentasPagosFecha(data.fechaemision)

                      setmontobs1PagoFact(data.montobs1? data.montobs1: "")
                      settasabs1PagoFact(data.tasabs1? data.tasabs1: "")
                      setmetodobs1PagoFact(data.metodobs1? data.metodobs1: "")
                      setmontobs2PagoFact(data.montobs2? data.montobs2: "")
                      settasabs2PagoFact(data.tasabs2? data.tasabs2: "")
                      setmetodobs2PagoFact(data.metodobs2? data.metodobs2: "")
                      setmontobs3PagoFact(data.montobs3? data.montobs3: "")
                      settasabs3PagoFact(data.tasabs3? data.tasabs3: "")
                      setmetodobs3PagoFact(data.metodobs3? data.metodobs3: "")
                      setmontobs4PagoFact(data.montobs4? data.montobs4: "")
                      settasabs4PagoFact(data.tasabs4? data.tasabs4: "")
                      setmetodobs4PagoFact(data.metodobs4? data.metodobs4: "")
                      setmontobs5PagoFact(data.montobs5? data.montobs5: "")
                      settasabs5PagoFact(data.tasabs5? data.tasabs5: "")
                      setmetodobs5PagoFact(data.metodobs5? data.metodobs5: "")

                      setrefbs1PagoFact(data.refbs1? data.refbs1: "")
                      setrefbs2PagoFact(data.refbs2? data.refbs2: "")
                      setrefbs3PagoFact(data.refbs3? data.refbs3: "")
                      setrefbs4PagoFact(data.refbs4? data.refbs4: "")
                      setrefbs5PagoFact(data.refbs5? data.refbs5: "")


                      let pagoSIds = []
                      data.facturas.map(e=>{
                        pagoSIds.push({
                          id: e.pivot.id_factura,
                        })
                      })
                      selectCuentaPorPagarProveedorDetallesFun("buscar",data.id_proveedor,pagoSIds.map(e=>e.id),res=>{
                        let pagos = [] 

                        
                        data.facturas.map(e=>{
                          let filterData = res.filter(ee=>ee.id==e.pivot.id_factura)
                          if (filterData.length) {
                            pagos.push({
                              id: e.pivot.id_factura,
                              val: e.pivot.monto,
                              valfact: e.monto,
                              numfact: e.numfact,
    
                              sucursal: filterData[0].sucursal,
                              proveedor: filterData[0].proveedor,
                              fechaemision: filterData[0].fechaemision,
                              fechavencimiento: filterData[0].fechavencimiento,
                              monto_bruto: filterData[0].monto_bruto,
                              monto_descuento: filterData[0].monto_descuento,
                              descuento: filterData[0].descuento,
                              aprobado: filterData[0].aprobado,
                              condicion: filterData[0].condicion,
                              monto: filterData[0].monto,
                              monto_abonado: filterData[0].monto_abonado,

                              balance: filterData[0].balance,
              
                              guardado:true,
                            })
                            
                          }
                        })
                        setselectAbonoFact(pagos)

                      })


                    }else{
                      setselectFactEdit(id)
                      setcuentasporpagarDetallesView("pagos")
                      setsubviewAgregarFactPago("factura")

                      setnewfactid_proveedor(data.id_proveedor)
                      setnewfactnumfact(data.numfact)
                      setnewfactnumnota(data.numnota)
                      setnewfactdescripcion(data.descripcion)
                      setnewfactsucursal(data.sucursal.codigo)
                      setnewfactsubtotal(number(data.subtotal))
                      setnewfactdescuento(number(data.descuento))
                      setnewfactmonto_exento(number(data.monto_exento))
                      setnewfactmonto_gravable(number(data.monto_gravable))
                      setnewfactiva(number(data.iva))
                      setnewfactmonto(number(data.monto_bruto))
  
                      setnewfactfechaemision(data.fechaemision)
                      setnewfactfechavencimiento(data.fechavencimiento)
                      setnewfactfecharecepcion(data.fecharecepcion)

                      setnewfactnota(data.nota)
                      setnewfacttipo(data.tipo)
                      setnewfactfrecuencia(data.frecuencia)
                    }
                }
            }
        }
    }
    
  }
  const saveNewFact = event => {
    event.preventDefault()
    if (confirm("Confirme")) {
      db.saveNewFact({
        newfactid_proveedor:selectProveedorCxp,
        newfactnumfact,
        newfactnumnota,
        newfactdescripcion,
        newfactsubtotal,
        newfactdescuento,
        newfactmonto_exento,
        newfactmonto_gravable,
        newfactiva,
        newfactmonto,
        newfactfechaemision,
        newfactfechavencimiento,
        newfactfecharecepcion,
        newfactnota,
        newfacttipo,
        newfactfrecuencia,
        id:selectFactEdit
      }).then(res=>{
        notificar(res)
        if (res.data.estado) {
          setselectFactEdit(null)
          selectCuentaPorPagarProveedorDetallesFun()
          setcuentasporpagarDetallesView("cuentas")

          setnewfactid_proveedor("")
          setnewfactnumfact("")
          setnewfactnumnota("")
          setnewfactdescripcion("")
          setnewfactsucursal("")
          setnewfactsubtotal("")
          setnewfactdescuento("")
          setnewfactmonto_exento("")
          setnewfactmonto_gravable("")
          setnewfactiva("")
          setnewfactmonto("")
          setnewfactfechaemision("")
          setnewfactfechavencimiento("")
          setnewfactfecharecepcion("")
          setnewfactnota("")
          setnewfacttipo("")
          setnewfactfrecuencia("")
        }
      })
    }
    
  }
  const delCuentaPorPagar = (event, id) => {
    let numfact = event.currentTarget.attributes["data-numfact"].value

    let prompt = window.prompt("ESCRIBA EN MINÚSCULA: eliminar [numero de Factura]")
    let res = "eliminar "+numfact
    if (prompt==res) {
      db.delCuentaPorPagar({id}).then(res=>{
        if (res.data.estado) {
          setSelectCuentaPorPagarDetalle(null)
          setselectFactEdit(null)
          selectCuentaPorPagarProveedorDetallesFun()
        }
        notificar(res)
      })
    }
  }
  

  const selectFacturaSetPago = (id,numfact) => {
    setselectFactPagoArr({
      id,numfact
    })
  }
  const changeAprobarFact = (id) => {
    if (confirm("Confirme")) {
      db.changeAprobarFact({id}).then(res=>{
        selectCuentaPorPagarProveedorDetallesFun()
        setSelectCuentaPorPagarDetalle(null)
      })
    }
  }
  
  useEffect(()=>{
    selectCuentaPorPagarProveedorDetallesFun()
  },[
      categoriacuentasPorPagarDetalles,
      tipocuentasPorPagarDetalles,
      qcuentasPorPagarTipoFact,
      numcuentasPorPagarDetalles,
      OrdercuentasPorPagarDetalles,
      cuentaporpagarAprobado,
      sucursalcuentasPorPagarDetalles,
      qCampocuentasPorPagarDetalles,
  ])

  const selectCuentaPorPagarProveedorDetallesFun = (type="buscar",id_proveedor_force=null,id_facts_force=null,callback=null) => {
    let req = {
      id_proveedor: id_proveedor_force===null? selectProveedorCxp: id_proveedor_force,
      
      categoriacuentasPorPagarDetalles,
      tipocuentasPorPagarDetalles,
      qcuentasPorPagarTipoFact,
      numcuentasPorPagarDetalles,
      qCampocuentasPorPagarDetalles,
      qcuentasPorPagarDetalles,
      qcampoBusquedacuentasPorPagarDetalles,
      qinvertircuentasPorPagarDetalles,
      OrdercuentasPorPagarDetalles,
      cuentaporpagarAprobado,
      sucursalcuentasPorPagarDetalles,
      type,
      id_facts_force,
    }
    if (type=="buscar") {
      //setSelectCuentaPorPagarId([])

      db.selectCuentaPorPagarProveedorDetalles(req).then(res=>{
        if (res.data) {
          if (res.data.detalles.length) {
            setSelectCuentaPorPagarId(res.data)

            if (callback!==null) {
              callback(res.data.detalles)
            }
          }else{
            setSelectCuentaPorPagarId([])
          }
        }
      })
    }else if("reporte") {
      db.selectCuentaPorPagarProveedorDetallesREPORTE(req)
    }
  }
  const getsucursalDetallesData = (event = null, subviewpanelsucursalesforce = null) => {

    if (event) {
      event.preventDefault()
    }
    setLoading(true)
    db.getsucursalDetallesData({
      fechasMain1,
      fechasMain2,
      filtros: {
        itemCero: invsuc_itemCero,
        q: invsuc_q,
        exacto: invsuc_exacto,
        num: invsuc_num,
        orderColumn: invsuc_orderColumn,
        orderBy: invsuc_orderBy,
        controlefecSelectGeneral,
        filtronominaq,
        filtronominacargo,
        qestatusaprobaciocaja,
        qfiltroaprotransf,
        bancoqfiltroaprotransf,

        qcuentasPorPagar,
        controlefecSelectCat,
        controlefecQDescripcion,
      },

      subviewpanelsucursales: subviewpanelsucursalesforce ? subviewpanelsucursalesforce : subviewpanelsucursales,
      sucursalSelect,
    }).then(res => {
      setsucursalDetallesData(res.data)
      setLoading(false)
    })
  }



  

  /// Nomina ///

  const setNuevoPersonal = () => {
		setnominaNombre("")
        setnominaCedula("")
        setnominaTelefono("")
        setnominaDireccion("")
        setnominaFechadeNacimiento("")
        setnominaFechadeIngreso("")
        setnominaGradoInstruccion("")
        setnominaCargo("")
        setnominaSucursal("")
		setIndexSelectNomina(null)
		setnominaid_sucursal_disponible("")
		setnominaactivo("")
	}
  const selectNominaDetalles = id => {
    setnominapagodetalles({})
    let personal = nominaData.personal
    if (personal) {
      let nomina = personal.filter(e => e.id === id)
      if (nomina) {
        setnominapagodetalles(nomina[0])
      }
    }
  }

  const delPersonalNomina = event => {
    event.preventDefault()
    db.delPersonalNomina({
      id: indexSelectNomina
    }).then(({ data }) => {
      if (data.estado) {
        getPersonalNomina()
      }
      notificar(data.msj)
    })
  }
  const addPersonalNomina = event => {
    event.preventDefault()

    db.setPersonalNomina({
      nominaNombre,
      nominaCedula,
      nominaTelefono,
      nominaDireccion,
      nominaFechadeNacimiento,
      nominaFechadeIngreso,
      nominaGradoInstruccion,
      nominaCargo,
      nominaSucursal,
      nominaid_sucursal_disponible,
      nominaactivo,

      id: indexSelectNomina
    }).then(({ data }) => {
      if (data.estado) {
        getPersonalNomina()
        setshownewpersonal(false)
        setNuevoPersonal()
      }
      notificar(data.msj)
    })
  }
  const activarPersonal = id => {
    if (confirm("Confirme")) {
      db.activarPersonal({id}).then(res=>{
        getPersonalNomina()
      }) 
    }
  }
  const getPersonalNomina = event => {
    if (event) {
      event.preventDefault()
    }
    db.getPersonalNomina({
      qSucursalNominaFecha,
      fechasMain1,
      fechasMain2,
      qNomina,
      qSucursalNomina,
      qCargoNomina,
      qSucursalNominaOrden,
      qSucursalNominaOrdenCampo,
      qSucursalNominaEstatus,
      type: subViewNomina
    }).then(({ data }) => {
      setnominaData(data)
    })
  }
  const getPersonal = (callback=null) => {
    db.getPersonalNomina({
      qNomina,
      type: "buscar"
    }).then(({ data }) => {
      setnominaData(data)
      if (callback!==null) {
        callback(data)
      }
    })
  }

  ///PUNTOS Y SERIALES
  

  const changeLiquidacionPagoElec = (id) => {
    if (confirm("CONFIRMAR LIQUIDACIÓN")) {
      db.changeLiquidacionPagoElec({id}).then(res=>{
        if (res.data) {
          getsucursalDetallesData()
        }
      })
    }
  }

  ////Cargos

  const delPersonalCargos = () => {
    db.delPersonalCargos({
      id: indexSelectCargo
    }).then(({ data }) => {
      if (data.estado) {
        getPersonalCargos()
      }
      notificar(data.msj)
    })
  }
  const addPersonalCargos = event => {
    event.preventDefault()
    db.setPersonalCargos({
      cargosDescripcion,
      cargosSueldo,
      id: indexSelectCargo,
    }).then(({ data }) => {
      if (data.estado) {
        getPersonalCargos(null)
      }
      notificar(data.msj)

    })
  }
  const getPersonalCargos = event => {
    if (event) {
      event.preventDefault()
    }

    db.getPersonalCargos({
      qCargos
    }).then(({ data }) => {
      setcargosData(data)
    })
  }
  

  const setInputsUsuarios = () => {
    if (indexSelectUsuarios) {
      let obj = usuariosData[indexSelectUsuarios];
      if (obj) {
        setusuarioNombre(obj.nombre);
        setusuarioUsuario(obj.usuario);
        setusuarioRole(obj.tipo_usuario);
        setusuarioId_sucursal(obj.id_sucursal);
        setusuarioArea(obj.area);
        setusuarioClave(obj.clave);
      }
    }
  };
  const getUsuarios = () => {
    setLoading(true);
    db.getUsuarios({ q: qBuscarUsuario }).then((res) => {
      setLoading(false);
      setusuariosData(res.data);
    });
  };
  const delUsuario = () => {
    setLoading(true);
    let id = null;
    if (indexSelectUsuarios) {
      id = usuariosData[indexSelectUsuarios].id;
    }
    db.delUsuario({ id }).then((res) => {
      setLoading(false);
      getUsuarios();
      notificar(res);
    });
  };
  const addNewUsuario = (e) => {
    e.preventDefault();
    let id = null;
    if (indexSelectUsuarios) {
      id = usuariosData[indexSelectUsuarios].id;
    }
    if (usuarioRole && usuarioNombre && usuarioUsuario) {
      setLoading(true);
      db.setUsuario({
        id,
        role: usuarioRole,
        nombres: usuarioNombre,
        usuario: usuarioUsuario,
        clave: usuarioClave,
        area: usuarioArea,
        id_sucursal: usuarioId_sucursal,
      }).then((res) => {
        notificar(res);
        setLoading(false);
        getUsuarios();
      });
    } else {
      console.log(
        "Err: addNewUsuario" +
        usuarioRole +
        " " +
        usuarioNombre +
        " " +
        usuarioUsuario
      );
    }
  };
  const getProductos = (valmain = null, itemCeroForce = null) => {
    setpermisoExecuteEnter(false);
    setLoading(true);

    if (time != 0) {
      clearTimeout(typingTimeout);
    }

    if (view == "seleccionar") {
      if (inputbusquedaProductosref.current) {
        valmain = inputbusquedaProductosref.current.value;
      }
    }

    let time = window.setTimeout(() => {
      db.getinventario({
        vendedor: showMisPedido ? [user.id_usuario] : [],
        num,
        itemCero: itemCeroForce ? itemCeroForce : itemCero,
        qProductosMain: valmain ? valmain : qProductosMain,
        orderColumn,
        orderBy,
      }).then((res) => {
        if (res.data) {
          if (res.data.estado === false) {
            notificar(res.data.msj, false)
          }
          let len = res.data.length;
          if (len) {
            setProductos(res.data);
          }
          if (!len) {
            setProductos([]);
          }

        }
        setLoading(false);
      });
      setpermisoExecuteEnter(true);
    }, 150);
    setTypingTimeout(time);
  };

  ///Compras Functions
  const openSelectProvNewPedCompras = (id) => {
    setopenSelectProvNewPedComprasCheck(true)
    setNewPedComprasSelectProd(id)
  }
  const selectPrecioxProveedorSave = () => {
    db.selectPrecioxProveedorSave({
      id_producto: selectPrecioxProveedorProducto,
      id_proveedor: selectPrecioxProveedorProveedor,
      precio: selectPrecioxProveedorPrecio
    }).then(res => {
      getPrecioxProveedor()
    })
  }

  const getPrecioxProveedor = (id_producto_force = null) => {
    db.getPrecioxProveedor({
      id_producto: id_producto_force ? id_producto_force : selectPrecioxProveedorProducto,
    }).then(res => {
      setprecioxproveedor(res.data)
    })
  }
  const [selectAbonoFact, setselectAbonoFact] = useState([])
  const setInputAbonoFact = (id,val) => {
    let selectAbonoFactClone = cloneDeep(selectAbonoFact)
    if (selectAbonoFactClone.concat(selectCuentaPorPagarId?selectCuentaPorPagarId.detalles? selectCuentaPorPagarId.detalles: ([]): ([]))) {
      if (selectAbonoFactClone.concat(selectCuentaPorPagarId?selectCuentaPorPagarId.detalles? selectCuentaPorPagarId.detalles: ([]): ([])).length) {
          let fil = selectAbonoFactClone.concat(selectCuentaPorPagarId?selectCuentaPorPagarId.detalles? selectCuentaPorPagarId.detalles: ([]): ([])).filter(e=>e.id==id)
          if (fil.length) {
            let exclude = selectAbonoFactClone.map(e=>{
              if (e.id==id) {
                e.val=val
              }
              return e
            })

            if (!selectAbonoFactClone.filter(e=>e.id==id).length) {
              let setAb = {
                id,
                val:number(val),
                valfact: fil[0].monto,
                numfact: fil[0].numfact,

                sucursal: fil[0].sucursal,
                proveedor: fil[0].proveedor,
                fechaemision: fil[0].fechaemision,
                fechavencimiento: fil[0].fechavencimiento,
                monto_bruto: fil[0].monto_bruto,
                monto_descuento: fil[0].monto_descuento,
                descuento: fil[0].descuento,
                aprobado: fil[0].aprobado,
                condicion: fil[0].condicion,
                monto: fil[0].monto,
                monto_abonado: fil[0].monto_abonado,
                balance: fil[0].balance,

                guardado:true,
              }
              exclude = exclude.concat(setAb)
            }
            if (val=="") {
              exclude = exclude.filter(e=>e.id!=id)
            }


            setselectAbonoFact(exclude)
          }
        }
      }
  }
  const sendPagoCuentaPorPagar = (e) => {

    if (e) {
      e.preventDefault()
    }
    if (confirm("Confirme pago")) {
      if (selectProveedorCxp) {
        
        let chetotal = selectAbonoFact.map(e=>number(e.val)).reduce((partial_sum, a) => parseFloat(partial_sum) + parseFloat(a), 0)

        if (parseFloat(cuentasPagosMonto)<chetotal+1 && parseFloat(cuentasPagosMonto)>chetotal-1) {
          db.sendPagoCuentaPorPagar({
            id: selectFactPagoid,
            id_sucursal: selectFactPagoid_sucursal,
            cuentasPagosDescripcion,
            cuentasPagosMonto,
            cuentasPagosMetodo,
            cuentasPagosFecha,
            id_pro:selectProveedorCxp,
            selectAbonoFact,

          
            montobs1PagoFact,
            tasabs1PagoFact,
            metodobs1PagoFact,
            montobs2PagoFact,
            tasabs2PagoFact,
            metodobs2PagoFact,
            montobs3PagoFact,
            tasabs3PagoFact,
            metodobs3PagoFact,
            montobs4PagoFact,
            tasabs4PagoFact,
            metodobs4PagoFact,
            montobs5PagoFact,
            tasabs5PagoFact,
            metodobs5PagoFact,
            refbs1PagoFact,
            refbs2PagoFact,
            refbs3PagoFact,
            refbs4PagoFact,
            refbs5PagoFact,
          }).then(res=>{
            if (res.data.estado) {
              setcuentasporpagarDetallesView("cuentas")
              selectCuentaPorPagarProveedorDetallesFun()
              setselectAbonoFact([])

              setcuentasPagosMonto("")
              setcuentasPagosDescripcion("")
              setcuentasPagosMetodo("")
              setcuentasPagosFecha("")

              setmontobs1PagoFact("")
              settasabs1PagoFact("")
              setmetodobs1PagoFact("")
              setmontobs2PagoFact("")
              settasabs2PagoFact("")
              setmetodobs2PagoFact("")
              setmontobs3PagoFact("")
              settasabs3PagoFact("")
              setmetodobs3PagoFact("")
              setmontobs4PagoFact("")
              settasabs4PagoFact("")
              setmetodobs4PagoFact("")
              setmontobs5PagoFact("")
              settasabs5PagoFact("")
              setmetodobs5PagoFact("")
              setrefbs1PagoFact("")
              setrefbs2PagoFact("")
              setrefbs3PagoFact("")
              setrefbs4PagoFact("")
              setrefbs5PagoFact("")

              setdataselectFacts({
                "sum": 0,
                "data": []
              })
            }
            notificar(res.data.msj)
          })
        }else{
          alert("Montos no coinciden")
        }    
      }
    }
  }


  ////End Compras Functions

  //Proveedores Func
  const getProveedores = e => {
    if (time != 0) {
      clearTimeout(typingTimeout)
    }

    let time = window.setTimeout(() => {
      setLoading(true)
      db.getProveedores({
        q: qBuscarProveedor
      }).then(res => {
        setProveedoresList(res.data)
        setLoading(false)
        if (res.data.length === 1) {
          setIndexSelectProveedores(0)
        }
      })
    }, 150)
    setTypingTimeout(time)

    if (!categorias.length) {
      db.getCategorias({
      }).then(res => {
        setcategorias(res.data)
      })
    }
    if (!depositosList.length) {
      db.getDepositos({
        q: qBuscarProveedor
      }).then(res => {
        setdepositosList(res.data)
      })
    }


  }
  //End Proveedores Func

  /////Marcas 

  const [qBuscarMarcas, setQBuscarMarcas] = useState("");
  const [marcas, setmarcas] = useState([]);

  const [marcasDescripcion, setmarcasDescripcion] = useState("");
  const [indexSelectMarcas, setIndexSelectMarcas] = useState(null);

  const delMarcas = () => {
    setLoading(true);
    let id = null;
    if (indexSelectMarcas) {
      if (marcas[indexSelectMarcas]) {
        id = marcas[indexSelectMarcas].id;
      }
    }

    db.delMarca({ id }).then((res) => {
      setLoading(false);
      getMarcas();
      notificar(res);
      setIndexSelectMarcas(null);
    });
  };

  const addNewMarcas = (e) => {
    e.preventDefault();

    let id = null;
    if (indexSelectMarcas) {
      if (marcas[indexSelectMarcas]) {
        id = marcas[indexSelectMarcas].id;
      }
    }

    if (marcasDescripcion) {
      setLoading(true);
      db.setMarcas({ id, marcasDescripcion }).then((res) => {
        notificar(res);
        setLoading(false);
        getMarcas();
      });
    }
  };
  const getMarcas = () => {
    db.getMarcas({
      q: qBuscarMarcas,
    }).then((res) => {
      if (res.data) {
        if (res.data.length) {
          setmarcas(res.data);
        } else {
          setmarcas([]);
        }
      }
    });
  };

  ///END Marcas

  /////Categorias 

  const [qBuscarCategorias, setQBuscarCategorias] = useState("");
  const [categorias, setcategorias] = useState([]);

  const [categoriasDescripcion, setcategoriasDescripcion] = useState("");
  const [indexSelectCategorias, setIndexSelectCategorias] = useState(null);

  const delCategorias = () => {
    setLoading(true);
    let id = null;
    if (indexSelectCategorias) {
      if (categorias[indexSelectCategorias]) {
        id = categorias[indexSelectCategorias].id;
      }
    }

    db.delCategoria({ id }).then((res) => {
      setLoading(false);
      getCategorias();
      notificar(res);
      setIndexSelectCategorias(null);
    });
  };

  const addNewCategorias = (e) => {
    e.preventDefault();

    let id = null;
    if (indexSelectCategorias) {
      if (categorias[indexSelectCategorias]) {
        id = categorias[indexSelectCategorias].id;
      }
    }

    if (categoriasDescripcion) {
      setLoading(true);
      db.setCategorias({ id, categoriasDescripcion }).then((res) => {
        notificar(res);
        setLoading(false);
        getCategorias();
      });
    }
  };
  const getCategorias = () => {
    db.getCategorias({
      q: qBuscarCategorias,
    }).then((res) => {
      if (res.data) {
        if (res.data.length) {
          setcategorias(res.data);
        } else {
          setcategorias([]);
        }
      }
    });
  };
  const setInputsCats = () => {
    if (indexSelectCategorias) {
      let obj = categorias[indexSelectCategorias];
      if (obj) {
        setcategoriasDescripcion(obj.descripcion);
      }
    }
  };

  ///END Categorias

  /////CatGenerals 

  const [qBuscarCatGenerals, setQBuscarCatGenerals] = useState("");
  const [catGenerals, setcatGenerals] = useState([]);

  const [catGeneralsDescripcion, setcatGeneralsDescripcion] = useState("");
  const [indexSelectCatGenerals, setIndexSelectCatGenerals] = useState(null);

  const delCatGenerals = () => {
    setLoading(true);
    let id = null;
    if (indexSelectCatGenerals) {
      if (catGenerals[indexSelectCatGenerals]) {
        id = catGenerals[indexSelectCatGenerals].id;
      }
    }

    db.delCatGeneral({ id }).then((res) => {
      setLoading(false);
      getCatGenerals();
      notificar(res);
      setIndexSelectCatGenerals(null);
    });
  };

  const addNewCatGenerals = (e) => {
    e.preventDefault();

    let id = null;
    if (indexSelectCatGenerals) {
      if (catGenerals[indexSelectCatGenerals]) {
        id = catGenerals[indexSelectCatGenerals].id;
      }
    }

    if (catGeneralsDescripcion) {
      setLoading(true);
      db.setCatGenerals({ id, catGeneralsDescripcion }).then((res) => {
        notificar(res);
        setLoading(false);
        getCatGenerals();
      });
    }
  };
  const getCatGenerals = () => {
    db.getCatGenerals({
      q: qBuscarCatGenerals,
    }).then((res) => {
      if (res.data) {
        if (res.data.length) {
          setcatGenerals(res.data);
        } else {
          setcatGenerals([]);
        }
      }
    });
  };
  const [categoriasCajas, setcategoriasCajas] = useState([])
  const getCatCajas = () => {
    db.getCatCajas({}).then(res=>{
      if (res.data.length) {
        setcategoriasCajas(res.data)
      }
    })
  }

  ///END CatGenerals
  const type = type => {
    return !type || type === "delete" ? true : false
  }

  /* inventario
  cierres
  gastos
  nomina
  usuarios
  compras */

  let opcionesadmin = [
    {
      route: "efectivo",
      name: "CXP"
    },
    {
      route: "creditos",
      name: "CXC"
    },
    {
      route: "auditoria",
      name: "CONCILIACIÓN"
    },
    {
      route: "gastos",
      name: "FLUJO DE CAJA"
    },
    {
      route: "compras",
      name: "COMPRAS"
    },
    {
      route: "nomina",
      name: "RRHH"
    },


    {
      route: "usuarios",
      name: "USUARIOS"
    },
    {
      route: "alquileres",
      name: "ALQUILERES"
    },
  ]

  let opcionesgeneral = [
    {
      route: "sucursales",
      name: "SUCURSALES"
    },
    {
      route: "comovamos",
      name: "CÓMO VAMOS"
    },
    {
      route: "dici",
      name: "DICI"
    },
    {
      route: "administracion",
      name: "ADMINISTRACIÓN"
    },

  ]

  const [showimportliquidacion,setshowimportliquidacion] = useState(false)
  const [textimportliquidadcion,settextimportliquidadcion] = useState("")
  const [dataimportliquidacion,setdataimportliquidacion] = useState([])
  
  const procesarImportTextliquidacion = () => {
    
    //     [CODIGO BANCO] 
      //     [FECHA yyyy-mm-dd] 
      //     [REF] 
      //     [MONTO]
    
    let obj = []
    let rows = textimportliquidadcion.replace("\"","").replace("\'","").split("\n")
    let cols,row, fecha,codigo,ref,monto;
    if (textimportliquidadcion) {
      for(let i in rows){
        row = rows[i]
        cols = row.split("\t")
        
        if (typeof cols[0]==="undefined") {alert("Col [1] no está definida")}
        if (typeof cols[1]==="undefined") {alert("Col [2] no está definida")}
        if (typeof cols[2]==="undefined") {alert("Col [3] no está definida")}
        if (typeof cols[3]==="undefined") {alert("Col [4] no está definida")}

        if (
          typeof cols[0]==="undefined"
          ||typeof cols[1]==="undefined"
          ||typeof cols[2]==="undefined"
          ||typeof cols[3]==="undefined"
        ) {
          break
        }
  
        codigo = cols[0]?cols[0]:""
        fecha = cols[1]?cols[1]:""
        ref = cols[2]?cols[2]:""
        monto = cols[3]?cols[3].replace(",","."):""


  
        let newObj = [{
          fecha,
          codigo,
          ref,
          monto,
        }]
  
        obj = newObj.concat(obj)
      }
      setdataimportliquidacion(obj)
      setshowimportliquidacion(false)
    }
  }

 

  const [opcionesMetodosPago,setopcionesMetodosPago] = useState([])
  const [bancosdata,setbancosdata] = useState([])
  const [fechaSelectAuditoria,setfechaSelectAuditoria] = useState("")
  const [showallSelectAuditoria,setshowallSelectAuditoria] = useState("")
  const [tipoSelectAuditoria,settipoSelectAuditoria] = useState("")
  const [ingegreSelectAuditoria,setingegreSelectAuditoria] = useState("")
  const [fechaHastaSelectAuditoria,setfechaHastaSelectAuditoria] = useState("")
  const [bancoSelectAuditoria,setbancoSelectAuditoria] = useState("")
  const [sucursalSelectAuditoria,setsucursalSelectAuditoria] = useState("")
  const [qdescripcionbancosdata,setqdescripcionbancosdata] = useState([])
  const [SaldoInicialSelectAuditoria,setSaldoInicialSelectAuditoria] = useState("")
  const [SaldoActualSelectAuditoria,setSaldoActualSelectAuditoria] = useState("")
  const [movimientoAuditoria,setmovimientoAuditoria] = useState([])

  const [selectTrLiquidar,setselectTrLiquidar] = useState(null)

  const [inpmontoLiquidar,setinpmontoLiquidar] = useState("")
  const [inpfechaLiquidar,setinpfechaLiquidar] = useState("")
  
  const [orderAuditoria,setorderAuditoria] = useState("desc")
  const [orderColumnAuditoria,setorderColumnAuditoria] = useState("tipo")
  const [saldoactualbancofecha,setsaldoactualbancofecha] = useState("")

  const [bancocuadres_debetenersegunsistema, setbancocuadres_debetenersegunsistema] = useState("")
  const [bancocuadres_saldo_inicial, setbancocuadres_saldo_inicial] = useState("")
  const [bancocuadres_ingreso, setbancocuadres_ingreso] = useState("")
  const [bancocuadres_egreso, setbancocuadres_egreso] = useState("")
  const [bancocuadres_sireportadasum, setbancocuadres_sireportadasum] = useState("")

  const [selectConciliacionData,setselectConciliacionData] = useState("")

  const selectConciliacion = (banco,fecha) => {
    
    setselectConciliacionData(banco+"-"+fecha)
    let fil = bancosdata.xfechaCuadre.filter(e=>e.banco==banco && e.fecha==fecha)
    if (fil.length) {
      let data = fil[0]
      let g = fil[0].guardado
      setbancocuadres_debetenersegunsistema(data.balance)
      setbancocuadres_saldo_inicial(data.inicial)
      setbancocuadres_ingreso(data.ingreso)
      setbancocuadres_egreso(data.egreso)
      setbancocuadres_sireportadasum(data.sireportadasum)
      
      if (g) {
        setsaldoactualbancofecha(g.saldo_real_manual)
      }else{
        setsaldoactualbancofecha("")
       }
    }
  }

  const sendsaldoactualbancofecha = (banco,fecha) => {
    console.log(banco)
    db.sendsaldoactualbancofecha({
      banco,
      fecha,
      saldo: saldoactualbancofecha,

      debetenersegunsistema: bancocuadres_debetenersegunsistema,
      saldo_inicial: bancocuadres_saldo_inicial,
      ingreso: bancocuadres_ingreso,
      egreso: bancocuadres_egreso,
      egreso: bancocuadres_egreso,
      bancocuadres_sireportadasum,
    }).then(res=>{
      getBancosData()
      setselectConciliacionData("")
      notificar(res.data.msj)
    })
  }


  const liquidarMov = id => {
    db.liquidarMov({
      id,
      monto: inpmontoLiquidar,
      fecha: inpfechaLiquidar,
    }).then(res=>{
      notificar(res)
      getBancosData("liquidar",true)
      setinpmontoLiquidar("")
      setselectTrLiquidar()

    })
  }
  const reverserLiquidar = id => {
    if (confirm("Confirme Reverso")) {
      db.reverserLiquidar({id}).then(res=>{
        getBancosData()
      })
    }
  }

  const [fechaAutoLiquidarTransferencia, setfechaAutoLiquidarTransferencia] = useState("")
  const [bancoAutoLiquidarTransferencia, setbancoAutoLiquidarTransferencia] = useState("")
  const autoliquidarTransferencia = type => {
    if (confirm("Confirme") && fechaAutoLiquidarTransferencia && bancoAutoLiquidarTransferencia) {
      db.autoliquidarTransferencia({
        type,
        fechaAutoLiquidarTransferencia,
        bancoAutoLiquidarTransferencia,
      }).then(res=>{
        getBancosData()

      })
    }
  }

  const [inpmontoNoreportado,setinpmontoNoreportado] = useState("")
  const [inpfechaNoreportado,setinpfechaNoreportado] = useState("")

  const reportarMov = id => {
    db.reportarMov({
      inpmontoNoreportado,
      inpfechaNoreportado,
      id,
    }).then(res=>{
      getBancosData()
      setinpmontoNoreportado("")
      setinpfechaNoreportado("")
      
    })
  }

  const changeBank = (id,type) => {
    let codigos = opcionesMetodosPago.map(e=>e.codigo)
    let banco = window.prompt("Editar "+type)
    if (banco) {
      switch (type) {
        case "banco":
          if (codigos.indexOf(banco)!=-1) {
            db.changeBank({id,banco,type})
            .then(res=>{
              getBancosData()
            })
          }else{
            alert("Código de Banco no está en la lista. "+banco)
          }
        break;
  
        case "debito_credito":
          if (banco=="DEBITO" || banco=="CREDITO") {
            db.changeBank({id,banco,type})
            .then(res=>{
              getBancosData()
            })
          }else{
            alert("Debe ser DEBITO o CREDITO. "+banco)
          }
        break;
  
        case "monto":
          db.changeBank({id, banco:number(banco), type})
          .then(res=>{
            getBancosData()
          })
        break;
  
      }
    }
  }
  const changeSucursal = id => {
    let codigos = sucursales.map(e=>e.codigo)
    let sucursal = window.prompt("Código de sucursal")
    if (codigos.indexOf(sucursal)!=-1) {
      db.changeSucursal({id,sucursal})
      .then(res=>{
        selectCuentaPorPagarProveedorDetallesFun()
      })
    }else{
      alert("Código de sucursal no está en la lista. "+sucursal)
    }
  }

  

  const selectxMovimientos = (type,typebanco) => {
    setmovimientoAuditoria([])
    let data = []
    if (bancosdata.puntosybiopagosxbancos) {
      let bancos = []
      if (!typebanco) {
        Object.entries(bancosdata.puntosybiopagosxbancos).map(e=>{
          bancos.push(e[0])
        })
      }else{
        bancos.push(typebanco)
      }

      bancos.forEach(banco => {
        if (bancosdata.puntosybiopagosxbancos[banco]) {
          if (bancosdata.puntosybiopagosxbancos[banco]["ingreso"]) {
            
            if (bancosdata.puntosybiopagosxbancos[banco]["ingreso"]["Transferencia"]) {
              if (type=="banco" || type=="ingreso_Transferencia") {
                data = data.concat(bancosdata.puntosybiopagosxbancos[banco]["ingreso"]["Transferencia"]["movimientos"])
              }
            }
            if (bancosdata.puntosybiopagosxbancos[banco]["ingreso"]["PUNTO"]) {
              if (type=="banco" || type=="ingreso_PUNTO") {
                data = data.concat(bancosdata.puntosybiopagosxbancos[banco]["ingreso"]["PUNTO"]["movimientos"])
              }
            }
            if (bancosdata.puntosybiopagosxbancos[banco]["ingreso"]["BIOPAGO"]) {
              if (type=="banco" || type=="ingreso_BIOPAGO") {
                data = data.concat(bancosdata.puntosybiopagosxbancos[banco]["ingreso"]["BIOPAGO"]["movimientos"])
              }
            }
          }
          if (bancosdata.puntosybiopagosxbancos[banco]["egreso"]) {
            if (bancosdata.puntosybiopagosxbancos[banco]["egreso"]["Transferencia"]) {
              if (type=="banco" || type=="egreso_Transferencia") {
                data = data.concat(bancosdata.puntosybiopagosxbancos[banco]["egreso"]["Transferencia"]["movimientos"])
              }
            }
          }
          setmovimientoAuditoria(data)
        }
      });
    }
     
  }
  const getMetodosPago = () => {
    db.getMetodosPago({
    }).then(res=>{
      setopcionesMetodosPago(res.data)
    })
  }

  const getBancosData = (subviewforced=null, recoveryAjuste=false) => {
    if (fechaSelectAuditoria && fechaHastaSelectAuditoria) {
      db.getBancosData({
        fechaSelectAuditoria,
        fechaHastaSelectAuditoria,
        tipoSelectAuditoria,
        ingegreSelectAuditoria,
        showallSelectAuditoria,

        bancoSelectAuditoria,
        sucursalSelectAuditoria,

        qdescripcionbancosdata,
        subviewAuditoria: subviewforced?subviewforced:subviewAuditoria,
        orderAuditoria,
        orderColumnAuditoria,
      }).then(res=>{
        setmovimientoAuditoria([])

        if (res.data.estado) {

          if (recoveryAjuste) {
            let ajustesbanco = bancosdata.xliquidar.filter(e=>e.ajuste).length        
            let ajustesreportado = bancosdata.xliquidar.filter(e=>e.ajuste).length  
                
            if (ajustesbanco || ajustesreportado) {

              let bancosdataclone = cloneDeep(bancosdata)
              let xliquidarclone = bancosdataclone.xliquidar.map(e=>{
                if (!e.ajuste) {
                  let fil = res.data.xliquidar.filter(ee=>ee.id==e.id)
                  if (fil.length) {
                    e = fil[0]
                  }
                }
                return e
              })
              bancosdataclone.xliquidar = xliquidarclone

              setbancosdata(bancosdataclone) 
            }else{
              setbancosdata(res.data)
            }
          }else{
            setbancosdata(res.data)
          }
        }else{
          notificar(res.data)
        }
      })
    }
  }

  const sendMovimientoBanco = event => {
    event.preventDefault()

    if (
      !cuentasPagosDescripcion ||
      !cuentasPagosMonto ||
      !cuentasPagosMetodo ||
      !cuentasPagosMetodoDestino
    ) {
      alert("Campos Vacíos!")      
    }else{
      db.sendMovimientoBanco({
        cuentasPagosDescripcion,
        cuentasPagosMonto:removeMoneda(cuentasPagosMonto),
        
        cuentasPagosMetodo,
        cuentasPagosMetodoDestino,
        cuentasPagosFecha,
        iscomisiongasto,
        comisionpagomovilinterban,
      }).then(res=>{
        if (res.data.estado) {
          getBancosData()
          setiscomisiongasto(0)
          setcuentasPagosDescripcion("")
          setcuentasPagosMonto("")
          setcuentasPagosMetodo("")
          setcuentasPagosMetodoDestino("")
          setcuentasPagosFecha("")
        }
        notificar(res.data.msj)
      })
    }
  }


  const [controlbancoQ,setcontrolbancoQ] = useState("")
  const [controlbancoQCategoria,setcontrolbancoQCategoria] = useState("")
  const [controlbancoQDesde,setcontrolbancoQDesde] = useState("")
  const [controlbancoQHasta,setcontrolbancoQHasta] = useState("")
  const [controlbancoQBanco,setcontrolbancoQBanco] = useState("")
  const [controlbancoQSiliquidado,setcontrolbancoQSiliquidado] = useState("")
  const [controlbancoQSucursal,setcontrolbancoQSucursal] = useState("")
  
  const [movBancosData,setmovBancosData] = useState([])
  

  const getMovBancos = () => {
    db.getMovBancos({
      controlbancoQ,
      controlbancoQCategoria,
      controlbancoQDesde,
      controlbancoQHasta,
      controlbancoQBanco,
      controlbancoQSiliquidado,
      controlbancoQSucursal,
    }).then(res=>{
      setmovBancosData(res.data)
    })
  }


  const [facturaSelectAddItems, setfacturaSelectAddItems] = useState(null)

  const [modeMoneda, setmodeMoneda] = useState("bs")
	const [modeEjecutor, setmodeEjecutor] = useState("personal")
  
  const [subViewCuentasxPagar, setsubViewCuentasxPagar] = useState("disponible")

  const [gastosData,setgastosData] = useState([])
  const [gastosQ,setgastosQ] = useState("")
  const [gastosQCategoria,setgastosQCategoria] = useState("")
  const [gastosQsucursal,setgastosQsucursal] = useState("")
  const [gastosQFecha,setgastosQFecha] = useState("")
  const [gastosQFechaHasta,setgastosQFechaHasta] = useState("")

  const [iscomisiongasto,setiscomisiongasto] = useState("")
  const [comisionpagomovilinterban,setcomisionpagomovilinterban] = useState("0.3")

  const [gastoscatgeneral, setgastoscatgeneral] = useState("")
  const [gastosingreso_egreso, setgastosingreso_egreso] = useState("")
  const [gastostypecaja, setgastostypecaja] = useState("")

  const [gastosorder, setgastosorder] = useState("desc")
  const [gastosfieldorder, setgastosfieldorder] = useState("id")
  
  const [gastosDescripcion,setgastosDescripcion] = useState("")
  const [gastosMonto,setgastosMonto] = useState("")
  const [gastosCategoria,setgastosCategoria] = useState("")
  const [gastosBeneficiario,setgastosBeneficiario] = useState("")
  const [gastosFecha,setgastosFecha] = useState("")
  const [gastosBanco,setgastosBanco] = useState("")
  const [gastosBancoDivisaDestino,setgastosBancoDivisaDestino] = useState("")

  const [gastosct,setgastosct] = useState("")
  const [gastosunidad,setgastosunidad] = useState("")
  


  const [distribucionGastosCat, setdistribucionGastosCat] = useState([])

  const [gastosMonto_dolar, setgastosMonto_dolar] = useState("")
  const [gastosTasa, setgastosTasa] = useState("")

  const [subviewGastos,setsubviewGastos] = useState("cargar")
  const [selectIdGastos,setselectIdGastos] = useState("")
  
  const [qBeneficiario,setqBeneficiario] = useState("")
  const [qSucursal,setqSucursal] = useState("")
  const [qCatGastos,setqCatGastos] = useState("")
  
  const [listBeneficiario, setlistBeneficiario] = useState([])

  
  const selectFactToDistribuirFun = (id_fact,id_sucursal) => {
    setfacturaSelectAddItems(id_fact);
    setsubviewDistribuir("distribuir")
    
    const myPromise = new Promise((resolve, reject) => {
        resolve("foo");
        setdistribucionSelectSucursal(id_sucursal)
    });
    myPromise
      .then(()=>{
        addlistdistribucionselect()
      })
      .then(()=>{
        autorepartircantidades("general",null)
      });

      
      
  }

  const addBeneficiarioList = (type,id=null) => {
    let fil = []
    if (modeEjecutor=="personal") {
      
      fil = nominaData.personal.filter(e=>e.id==(id!==null?id:gastosBeneficiario))
    }else{
      fil = sucursales.filter(e=>e.id==(id!==null?id:gastosBeneficiario))
    }
    let clone = (listBeneficiario)
    if (type=="add") {
      if (fil.length) {
        if (!listBeneficiario.filter(e=>e.id==(id!==null?id:gastosBeneficiario)).length) {
          
          setlistBeneficiario(clone.concat(fil[0]))
        }else{
          setlistBeneficiario(clone.filter(e=>e.id!=fil[0].id))

        }
      }
    }else{
      setlistBeneficiario(clone.filter(e=>e.id!=id))
    }
  }

  const delGasto = id => {
    db.delGasto({
      id
    }).then(res=>{
      notificar(res.data.msj)
      getGastos()
    })
  }
  const saveNewGasto = () => {
    if (confirm("Confirme")) {
      if (
        gastosDescripcion && 
        gastosCategoria &&
        gastosBanco &&
        gastosFecha &&
        gastosMonto && 
        gastosTasa
      ) {
        db.saveNewGasto({
          gastosct,
          gastosunidad,
          gastosDescripcion,
          gastosCategoria,
          gastosBeneficiario,
          gastosFecha,
          gastosBanco,
          controlefecNewMontoMoneda,
          gastosBancoDivisaDestino,
          gastosMonto: removeMoneda(gastosMonto),
          gastosMonto_dolar: removeMoneda(gastosMonto_dolar),
          gastosTasa:(gastosTasa),
          selectIdGastos,
          modeMoneda,
          modeEjecutor,
          listBeneficiario,
  
          iscomisiongasto,
          comisionpagomovilinterban,
        }).then(res=>{
          if (res.data.estado) {
            /* getGastos()
            getMovBancos() */
            setNewGastosInput()
            
          }
          notificar(res.data.msj)
        })
      }else{
        alert("Campos Vacíos")
      }
    }

  }
  const getGastosDistribucion = () => {
    db.getGastosDistribucion({
      gastosQFecha,
      gastosQFechaHasta,
      gastosQsucursal,
    })
    .then(res=>{
      let data = res.data
      setdistribucionGastosCat(data)
    })
  }

  const [qbuscarcat,setqbuscarcat] = useState("")
	const [indexviewcatdetalles,setindexviewcatdetalles] = useState(null)
	const [indexsubviewcatdetalles,setindexsubviewcatdetalles] = useState(null)
	const [indexsubviewproveedordetalles,setindexsubviewproveedordetalles] = useState(null)
  

	const [indexviewsucursaldetalles,setindexviewsucursaldetalles] = useState(null)
	const [indexsubviewsucursaldetalles,setindexsubviewsucursaldetalles] = useState(null)
  const getGastos = () => {
    db.getGastos({
      gastosQ,
      gastosQCategoria,
      gastosQsucursal,
      gastosQFecha,
      gastosQFechaHasta,
      gastoscatgeneral,
      gastosingreso_egreso,
      gastostypecaja,
      gastosorder,
      gastosfieldorder,
    }).then(res=>{
      if (res.data) {
        if (res.data.data.length) {
          setgastosData(res.data)
        }else{
          setgastosData([])
        }
      }
    })
  }
  const setNewGastosInput = () => {
    setgastosDescripcion("")
    setgastosMonto("")
    setgastosCategoria("")
    setgastosBeneficiario("")
    setgastosFecha("")
    setgastosMonto_dolar("")
    setgastosTasa("")
    setgastosBanco("")
    setlistBeneficiario([])
    setqbuscarcat("")
    setiscomisiongasto(0)
    setcontrolefecNewMontoMoneda("")

    setgastosct("")
    setgastosunidad("")
  }
  const setEditGastosInput = id => {
    let fil = gastosData.filter(e=>e.id===id)
    if (fil.length) {
      let dataFil = fil[0] 
      setgastosDescripcion(dataFil.loteserial)
      setgastosMonto(dataFil.monto_liquidado)
      setgastosCategoria(dataFil.categoria)
      setgastosBeneficiario(dataFil.id_beneficiario)
      setgastosFecha(dataFil.fecha_liquidacion)
      setgastosMonto_dolar(dataFil.monto_dolar)
      setgastosTasa(dataFil.tasa)
      setgastosBanco(dataFil.banco)
      
    }
  }

  const [selectIdVinculacion, setselectIdVinculacion] = useState([])
  
  const [qvinculacion1, setqvinculacion1] = useState("")
  const [qvinculacion2, setqvinculacion2] = useState("")
  const [qvinculacion3, setqvinculacion3] = useState("")
  const [qvinculacion4, setqvinculacion4] = useState("")
  const [qvinculacionmarca, setqvinculacionmarca] = useState("")
  const [qvinculacion5, setqvinculacion5] = useState("")
  const [qvinculaciocat, setqvinculaciocat] = useState("")
  const [qvinculaciocatesp, setqvinculaciocatesp] = useState("")
  const [qvinculacioproveedor, setqvinculacioproveedor] = useState("")
  const [qvinculaciomaxct, setqvinculaciomaxct] = useState("")
  const [qvinculaciominct, setqvinculaciominct] = useState("")

  const [qvinculacion1General, setqvinculacion1General] = useState("")
  const [qvinculacion2General, setqvinculacion2General] = useState("")
  const [qvinculacion3General, setqvinculacion3General] = useState("")
  const [qvinculacion4General, setqvinculacion4General] = useState("")
  const [qvinculacionmarcaGeneral, setqvinculacionmarcaGeneral] = useState("")
  const [qvinculacion5General, setqvinculacion5General] = useState("")
  const [qvinculaciocatGeneral, setqvinculaciocatGeneral] = useState("")
  const [qvinculaciocatespGeneral, setqvinculaciocatespGeneral] = useState("")
  const [qvinculacioproveedorGeneral, setqvinculacioproveedorGeneral] = useState("")
  const [qvinculaciomaxctGeneral, setqvinculaciomaxctGeneral] = useState("")
  const [qvinculaciominctGeneral, setqvinculaciominctGeneral] = useState("")
  
  const [datavinculacion1, setdatavinculacion1] = useState([])
  const [datavinculacion2, setdatavinculacion2] = useState([])
  const [datavinculacion3, setdatavinculacion3] = useState([])
  const [datavinculacion4, setdatavinculacion4] = useState([])
  const [datavinculacionmarca, setdatavinculacionmarca] = useState([])
  const [datavinculacion5, setdatavinculacion5] = useState([])
  const [datavinculaciocat, setdatavinculaciocat] = useState([])
  const [datavinculaciocatesp, setdatavinculaciocatesp] = useState([])
  const [datavinculacioproveedor, setdatavinculacioproveedor] = useState([])
  const [datavinculaciomaxct, setdatavinculaciomaxct] = useState([])
  const [datavinculaciominct, setdatavinculaciominct] = useState([])

  const [newNombre1,setnewNombre1] = useState("")
  const [newNombre2,setnewNombre2] = useState("")
  const [newNombre3,setnewNombre3] = useState("")
  const [newNombre4,setnewNombre4] = useState("")
  const [newNombremarca,setnewNombremarca] = useState("")
  const [newNombre5,setnewNombre5] = useState("")
  const [newNombrecat,setnewNombrecat] = useState("")
  const [newNombrecatesp,setnewNombrecatesp] = useState("")
  const [newNombreproveedor,setnewNombreproveedor] = useState("")
  const [newNombremaxct,setnewNombremaxct] = useState("")
  const [newNombreminct,setnewNombreminct] = useState("")

  
  const [inputselectvinculacion1, setinputselectvinculacion1] = useState("")
  const [inputselectvinculacion2, setinputselectvinculacion2] = useState("")
  const [inputselectvinculacion3, setinputselectvinculacion3] = useState("")
  const [inputselectvinculacion4, setinputselectvinculacion4] = useState("")
  const [inputselectvinculacion5, setinputselectvinculacion5] = useState("")
  const [inputselectvinculacioncat, setinputselectvinculacioncat] = useState("")
  const [inputselectvinculacioncatesp, setinputselectvinculacioncatesp] = useState("")
  const [inputselectvinculacionproveedor, setinputselectvinculacionproveedor] = useState("")
  const [inputselectvinculacionmaxct, setinputselectvinculacionmaxct] = useState("")
  const [inputselectvinculacionminct, setinputselectvinculacionminct] = useState("")
  const [inputselectvinculacionmarca, setinputselectvinculacionmarca] = useState("")
  
  
  
  const [inputselectvinculacion1General, setinputselectvinculacion1General] = useState("")
  const [inputselectvinculacion2General, setinputselectvinculacion2General] = useState("")
  const [inputselectvinculacion3General, setinputselectvinculacion3General] = useState("")
  const [inputselectvinculacion4General, setinputselectvinculacion4General] = useState("")
  const [inputselectvinculacion5General, setinputselectvinculacion5General] = useState("")
  const [inputselectvinculacioncatGeneral, setinputselectvinculacioncatGeneral] = useState("")
  const [inputselectvinculacioncatespGeneral, setinputselectvinculacioncatespGeneral] = useState("")
  const [inputselectvinculacionproveedorGeneral, setinputselectvinculacionproveedorGeneral] = useState("")
  const [inputselectvinculacionmaxctGeneral, setinputselectvinculacionmaxctGeneral] = useState("")
  const [inputselectvinculacionminctGeneral, setinputselectvinculacionminctGeneral] = useState("")
  const [inputselectvinculacionmarcaGeneral, setinputselectvinculacionmarcaGeneral] = useState("")
  
  const [subviewDistribuir, setsubviewDistribuir] = useState("selectfacttodistribuir") 
  const [listdistribucionselect, setlistdistribucionselect] = useState([]) 
  const [distribucionSelectSucursal, setdistribucionSelectSucursal] = useState("") 

  const [subviewcargaritemsfact, setsubviewcargaritemsfact] = useState("selectfacts")
  const [showtextarea, setshowtextarea] = useState(false)

  const removeMoneyFormat = num => {
    let n = num.toString()
    if (n.indexOf(",")===-1) {
      return num
    }
    if (n.indexOf(",")!==-1){
      return n.replace(".","").replace(",",".")
    }
  }
  const procesarTextitemscompras = () => {
    
    {/* 
      <th>ALTERNO</th>
      <th>UNIDAD</th>
      <th>DESCRIPCION</th>
      <th>CANTIDAD</th>
      <th>BASE F (CXP)</th> 
      <th>BASE</th> 
      <th>VENTA</th> 
    */}
    let obj = cloneDeep(productosInventario)
    let rows = inputimportitems.replace("\"","").replace("\'","").split("\n").reverse()
    let cols,row, alterno,barras,unidad,descripcion,ct,basef,base,venta;
    if (inputimportitems) {
      for(let i in rows){
        row = rows[i]
        cols = row.split("\t")
        
        if (typeof cols[0]==="undefined") {alert("Col [1] no está definida")}
        if (typeof cols[1]==="undefined") {alert("Col [2] no está definida")}
        if (typeof cols[2]==="undefined") {alert("Col [3] no está definida")}
        if (typeof cols[3]==="undefined") {alert("Col [4] no está definida")}
        if (typeof cols[4]==="undefined") {alert("Col [5] no está definida")}
        if (typeof cols[5]==="undefined") {alert("Col [6] no está definida")}
        if (typeof cols[6]==="undefined") {alert("Col [7] no está definida")}

        if (
          typeof cols[0]==="undefined"
          ||typeof cols[1]==="undefined"
          ||typeof cols[2]==="undefined"
          ||typeof cols[3]==="undefined"
          ||typeof cols[4]==="undefined"
          ||typeof cols[5]==="undefined"
          ||typeof cols[6]==="undefined"
        ) {
          break
        }
  
        alterno = cols[0]?cols[0]:""
        barras = cols[1]?cols[1]:""
        unidad = cols[2]?cols[2]:""
        descripcion = cols[3]?cols[3]:""
        ct = cols[4]?cols[4]:""
        basef = cols[5]?cols[5]:""
        base = cols[6]?cols[6]:""
        venta = cols[7]?cols[7]:""


  
        let newObj = [{
          id: null,
          codigo_proveedor: alterno,
          codigo_barras: barras,
          unidad: unidad,
          descripcion: descripcion,
          cantidad: removeMoneyFormat(ct),
          basef: removeMoneyFormat(basef),
          precio_base: removeMoneyFormat(base),
          precio: removeMoneyFormat(venta),
          id_categoria: "",
          id_catgeneral: "",
          iva: "0",
          type: "new",
          id_marca: "",
  
        }]
  
        obj = newObj.concat(obj)
      }
      setinputimportitems("")
      setProductosInventario(obj)
      setshowtextarea(false)
    }
  }
  const autorepartircantidades = (type,id_item) => {
    switch (type) {
      case "general":
        let facturaSelectAddItemsSelect = {}

        if (facturaSelectAddItems) {
          if (selectCuentaPorPagarId.detalles) {
            let match = selectCuentaPorPagarId.detalles.filter(e=>e.id==facturaSelectAddItems) 
            if (match.length) {
              facturaSelectAddItemsSelect = match[0]
              let list = cloneDeep(listdistribucionselect)
              facturaSelectAddItemsSelect.items.map(item=>{
                let num_suc = list.filter(l=>l.id_item==item.id).length
                let ct = item.cantidad
                list.map(l=>{
                  if (l.id_item == item.id) {
                    l.cantidad = ct/num_suc
                  }
                  return l
                })
                
              })
              setlistdistribucionselect(list)
            }
          }
        }

      break;

      case "item":
        
      break;
    }
  }

  const addlistdistribucionselect = () => {

    let facturaSelectAddItemsSelect = {}
    if (facturaSelectAddItems) {
        if (selectCuentaPorPagarId.detalles) {
            
          let match = selectCuentaPorPagarId.detalles.filter(e=>e.id==facturaSelectAddItems) 
          if (match.length) {
            facturaSelectAddItemsSelect = match[0]
            if (!listdistribucionselect.filter(e=>e.id_sucursal==distribucionSelectSucursal).length && distribucionSelectSucursal) {
              let list = cloneDeep(listdistribucionselect)
              facturaSelectAddItemsSelect.items.map(e=>{
                list.push({
                  id_sucursal: distribucionSelectSucursal,
                  id_item: e.id,
                  cantidad:0,
                })
              })
              setlistdistribucionselect(list)
            }

          }
        }
    }

  }

  const dellistdistribucionselect = (id_sucursal) => {
    let list = cloneDeep(listdistribucionselect)
    setlistdistribucionselect(list.filter(e=>e.id_sucursal!=id_sucursal))
  }

  const sendlistdistribucionselect = (id_cxp) => {
    if (confirm("CONFIRME")) {
      db.sendlistdistribucionselect({
        id:id_cxp,
      }).then(res=>{
        notificar(res)
        setlistdistribucionselect([])
      })
    }
  }

  const changeInputDistribuirpedido = (id_item,id_sucursal,value) => {
    let clone_listdistribucionselect = cloneDeep(listdistribucionselect)

    clone_listdistribucionselect.map(e=> {
      if (e.id_sucursal==id_sucursal && e.id_item==id_item) {
        e.cantidad = value
      }
      return e
    })

    setlistdistribucionselect(clone_listdistribucionselect)
  }

  const getDatinputSelectVinculacion = () => {
    db.getDatinputSelectVinculacion({}).then(res=>{
      let data = res.data
      setdatavinculacion1(data.datavinculacion1)
      setdatavinculacion2(data.datavinculacion2)
      setdatavinculacion3(data.datavinculacion3)
      setdatavinculacion4(data.datavinculacion4)
      setdatavinculacionmarca(data.datavinculacionmarca)

      
      setdatavinculacion5(data.datavinculacion5)
      setdatavinculaciocat(data.datavinculaciocat)
      setdatavinculaciocatesp(data.datavinculaciocatesp)
      setdatavinculacioproveedor(data.datavinculacioproveedor)
    })
  }

  const saveCuatroNombres = () => {
    db.saveCuatroNombres({
      selectIdVinculacion,
      inputselectvinculacion1,
      inputselectvinculacion2,
      inputselectvinculacion3,
      inputselectvinculacion4,



      inputselectvinculacion5,
      inputselectvinculacioncat,
      inputselectvinculacioncatesp,
      inputselectvinculacionproveedor,
      inputselectvinculacionmaxct,
      inputselectvinculacionminct,
   



      inputselectvinculacionmarca,
    }).then(res=>{
      buscarInventario()
      setselectIdVinculacion([])
      setinputselectvinculacion1("")
      setinputselectvinculacion2("")
      setinputselectvinculacion3("")
      setinputselectvinculacion4("")
      setinputselectvinculacionmarca("")

      setinputselectvinculacion1General("")
      setinputselectvinculacion2General("")
      setinputselectvinculacion3General("")
      setinputselectvinculacion4General("")
      setinputselectvinculacionmarcaGeneral("")


      setinputselectvinculacion5("")
      setinputselectvinculacioncat("")
      setinputselectvinculacioncatesp("")
      setinputselectvinculacionproveedor("")
      setinputselectvinculacionmaxct("")
      setinputselectvinculacionminct("")
      setinputselectvinculacion5General("")
      setinputselectvinculacioncatGeneral("")
      setinputselectvinculacioncatespGeneral("")
      setinputselectvinculacionproveedorGeneral("")
      setinputselectvinculacionmaxctGeneral("")
      setinputselectvinculacionminctGeneral("")



      setqvinculacion1("")
      setqvinculacion2("")
      setqvinculacion3("")
      setqvinculacion4("")
      setqvinculacionmarca("")

      setqvinculacion1General("")
      setqvinculacion2General("")
      setqvinculacion3General("")
      setqvinculacion4General("")
      setqvinculacionmarcaGeneral("")
    })
  }

  const addnewNombre = (palabra,type) =>{
    db.addnewNombre({
      palabra,
      type,
    }).then(res=>{
      getDatinputSelectVinculacion()
    })
  } 

  const permiso = (arrpermis) => {
    if (arrpermis.indexOf(user.tipo_usuario)!==-1) {
      return true
    }else{
      return false
    }
  }

  const returnCondicion = (condicion,type="btn") => {

    switch (condicion) {
      case "pagadas":
        return type+"-medsuccess";  
      break;
      case "vencidas":
        return type+"-danger";  
      break;
      case "porvencer":
        return type+"-sinapsis";  
      break;
      case "semipagadas":
        return type+"-primary";  
      break;
      case "abonos":
        return type+"-success";  
      break;
      default:
        return type+"-secondary"
      break;
    }

  }

  const [shownewmovnoreportado, setshownewmovnoreportado] = useState(false)
  const [newmovnoreportadoref, setnewmovnoreportadoref] = useState("")
  const [newmovnoreportadomonto, setnewmovnoreportadomonto] = useState("")
  const [newmovnoreportadobanco, setnewmovnoreportadobanco] = useState("")
  const [newmovnoreportadofecha, setnewmovnoreportadofecha] = useState("")
  
  const saveNewmovnoreportado = () => {
    if (
      newmovnoreportadoref ||
      newmovnoreportadomonto ||
      newmovnoreportadobanco ||
      newmovnoreportadofecha
    ) {
      db.saveNewmovnoreportado({
        newmovnoreportadoref,
        newmovnoreportadomonto: removeMoneda(newmovnoreportadomonto),
        newmovnoreportadobanco,
        newmovnoreportadofecha,
      }).then(res=>{
        if (res.data.estado) {
          setnewmovnoreportadomonto("")
          setnewmovnoreportadobanco("")
          setnewmovnoreportadofecha("")
          setshownewmovnoreportado(false)
        }
        notificar(res)
      })
    }
  }
  
  const [qauditoriaefectivo,setqauditoriaefectivo] = useState("")
  const [sucursalqauditoriaefectivo,setsucursalqauditoriaefectivo] = useState("")
  const [fechadesdeauditoriaefec,setfechadesdeauditoriaefec] = useState("")
  const [fechahastaauditoriaefec,setfechahastaauditoriaefec] = useState("")
  const [dataAuditoriaEfectivo,setdataAuditoriaEfectivo] = useState([])
  const [qcajaauditoriaefectivo,setqcajaauditoriaefectivo] = useState(1)

  const getAuditoriaEfec= () => {
    db.getAuditoriaEfec({
      qauditoriaefectivo,
      sucursalqauditoriaefectivo,
      fechadesdeauditoriaefec,
      fechahastaauditoriaefec,
      qcajaauditoriaefectivo,
    }).then(res=>{
      setdataAuditoriaEfectivo(res.data)
    })
  }

  const [sucursalqcuadregeneral,setsucursalqcuadregeneral] = useState("")
  const [fechadesdeqcuadregeneral,setfechadesdeqcuadregeneral] = useState("")
  const [fechahastaqcuadregeneral,setfechahastaqcuadregeneral] = useState("")
  const [datacuadregeneral,setdatacuadregeneral] = useState([])
  
  const getCuadreGeneral = () => {
    db.getCuadreGeneral({
      sucursalqcuadregeneral,
      fechadesdeqcuadregeneral,
      fechahastaqcuadregeneral,
    }).then(res=>{
      setdatacuadregeneral(res.data)
    })
  }
  
  const [fechareportediario, setfechareportediario] = useState("")
  
  const sendReporteDiario = (type) => {
    db.sendReporteDiario({type,fecha:fechareportediario})
  }

  
  const [sucursalBalanceGeneral, setsucursalBalanceGeneral] = useState("")
  const [fechaBalanceGeneral, setfechaBalanceGeneral] = useState("")
  const [fechaHastaBalanceGeneral, setfechaHastaBalanceGeneral] = useState("")
  const [balanceGeneralData, setbalanceGeneralData] = useState([])
  const [cuantotengobanco,setcuantotengobanco] = useState("")
  const [cuantotengoefectivo,setcuantotengoefectivo] = useState("")

  const sendCuadreGeneral = () => {
    if (confirm("Confirme")) {
        getBalanceGeneral()
    }
  }

  const getBalanceGeneral = () => {
    db.getBalanceGeneral({
      sucursalBalanceGeneral,
      fechaBalanceGeneral,
      fechaHastaBalanceGeneral,
    })
    .then(res=>{
      setbalanceGeneralData(res.data)
    })
  }

 /*  useState(()=>{
    getBalanceGeneral()
  },[
    sucursalBalanceGeneral,
    fechaBalanceGeneral,
    fechaHastaBalanceGeneral,
  ]) */
  

  let numfact_select_imagen = null
  if (selectFilecxp) {
      if (dataFilescxp.cuentasporpagar_fisicas) {
          
          let fil_selectFilecxp = dataFilescxp.cuentasporpagar_fisicas.filter(e=>e.id==selectFilecxp)
          if (fil_selectFilecxp.length) {
              numfact_select_imagen = fil_selectFilecxp[0]
          } 
      }
  }
  const logout = () => {
    db.logout({}).then(res=>{
      location.reload()
    })
  }
  
  return (
    <>
      {!loginActive ? <Login 
        loginRes={loginRes}
        
      /> : <>
        {msj != "" ? <Notificacion msj={msj} notificar={notificar} /> : null}

        {loading ? <Cargando active={loading} /> : null}

        <Panel>
          <Header
            logout={logout}
            viewmainPanel={viewmainPanel}
            setviewmainPanel={setviewmainPanel}
            sucursalSelect={sucursalSelect}
            setsucursalSelect={setsucursalSelect}
            sucursales={sucursales}

          />

          {viewmainPanel === "panelgeneral" &&
            <PanelOpciones
              viewmainPanel={viewmainPanel}
              setviewmainPanel={setviewmainPanel}
              opciones={opcionesgeneral}

            />
          }

          {viewmainPanel === "administracion" &&
            <PanelOpciones
              viewmainPanel={viewmainPanel}
              setviewmainPanel={setviewmainPanel}
              opciones={opcionesadmin}
            />
          }

          {permiso([1,2,5]) && viewmainPanel === "nomina" &&
            <>

              {/* {subViewNomina === "gestion" &&
                <Nomina
                  subViewNominaGestion={subViewNominaGestion}
                  setsubViewNominaGestion={setsubViewNominaGestion}
                >
                </Nomina>
              } */}
              {subViewNominaGestion === "personal" &&
                <NominaPersonal
                  setshownewpersonal={setshownewpersonal}
                  shownewpersonal={shownewpersonal}
                  nominaactivo={nominaactivo}
                  setnominaactivo={setnominaactivo}
                  activarPersonal={activarPersonal}
                  nominaNombre={nominaNombre}
                  setnominaNombre={setnominaNombre}
                  nominaCedula={nominaCedula}
                  setnominaCedula={setnominaCedula}
                  nominaTelefono={nominaTelefono}
                  setnominaTelefono={setnominaTelefono}
                  nominaDireccion={nominaDireccion}
                  setnominaDireccion={setnominaDireccion}
                  nominaFechadeNacimiento={nominaFechadeNacimiento}
                  setnominaFechadeNacimiento={setnominaFechadeNacimiento}
                  nominaFechadeIngreso={nominaFechadeIngreso}
                  setnominaFechadeIngreso={setnominaFechadeIngreso}
                  nominaGradoInstruccion={nominaGradoInstruccion}
                  setnominaGradoInstruccion={setnominaGradoInstruccion}
                  nominaCargo={nominaCargo}
                  setnominaCargo={setnominaCargo}
                  nominaSucursal={nominaSucursal}
                  setnominaSucursal={setnominaSucursal}
                  nominaid_sucursal_disponible={nominaid_sucursal_disponible}
                  setnominaid_sucursal_disponible={setnominaid_sucursal_disponible}
                  indexSelectNomina={indexSelectNomina}
                  setIndexSelectNomina={setIndexSelectNomina}

                  qSucursalNominaOrden={qSucursalNominaOrden}
                  setqSucursalNominaOrden={setqSucursalNominaOrden}
                  qSucursalNominaOrdenCampo={qSucursalNominaOrdenCampo}
                  setqSucursalNominaOrdenCampo={setqSucursalNominaOrdenCampo}
                  qSucursalNominaEstatus={qSucursalNominaEstatus}
                  setqSucursalNominaEstatus={setqSucursalNominaEstatus}
                  qNomina={qNomina}
                  setqNomina={setqNomina}
                  qSucursalNomina={qSucursalNomina}
                  setqSucursalNomina={setqSucursalNomina}
                  qCargoNomina={qCargoNomina}
                  setqCargoNomina={setqCargoNomina}
                  nominaData={nominaData}
                  setnominaData={setnominaData}
                  delPersonalNomina={delPersonalNomina}
                  addPersonalNomina={addPersonalNomina}
                  getPersonalNomina={getPersonalNomina}

                  cargosData={cargosData}
                  getPersonalCargos={getPersonalCargos}
                  sucursales={sucursales}
                  subViewNominaGestion={subViewNominaGestion}
                  setsubViewNominaGestion={setsubViewNominaGestion}
                  nominapagodetalles={nominapagodetalles}
                  getSucursales={getSucursales}

                  subViewNomina={subViewNomina}
                  selectNominaDetalles={selectNominaDetalles}
                  setnominapagodetalles={setnominapagodetalles}
                  moneda={moneda}
                  number={number}
                  setNuevoPersonal={setNuevoPersonal}
                  colorSucursal={colorSucursal}
                  qSucursalNominaFecha={qSucursalNominaFecha}
                  setqSucursalNominaFecha={setqSucursalNominaFecha}
                >
                </NominaPersonal>
              }
              {subViewNominaGestion === "cargos" &&
                <NominaCargos
                  cargosDescripcion={cargosDescripcion}
                  setcargosDescripcion={setcargosDescripcion}
                  cargosSueldo={cargosSueldo}
                  setcargosSueldo={setcargosSueldo}
                  qCargos={qCargos}
                  setqCargos={setqCargos}
                  indexSelectCargo={indexSelectCargo}
                  setindexSelectCargo={setindexSelectCargo}
                  cargosData={cargosData}
                  setcargosData={setcargosData}
                  delPersonalCargos={delPersonalCargos}
                  addPersonalCargos={addPersonalCargos}
                  getPersonalCargos={getPersonalCargos}
                  subViewNominaGestion={subViewNominaGestion}
                  setsubViewNominaGestion={setsubViewNominaGestion}

                >
                </NominaCargos>
              }
              {/* {subViewNomina === "pagos" &&
                <NominaPagos
                  qSucursalNomina={qSucursalNomina}
                  setqSucursalNomina={setqSucursalNomina}
                  sucursales={sucursales}
                  qCargoNomina={qCargoNomina}
                  setqCargoNomina={setqCargoNomina}
                  cargosData={cargosData}
                  qNomina={qNomina}
                  setqNomina={setqNomina}
                  getPersonalNomina={getPersonalNomina}
                  getPersonalCargos={getPersonalCargos}
                  nominaData={nominaData}
                  subViewNomina={subViewNomina}

                  selectNominaDetalles={selectNominaDetalles}

                  nominapagodetalles={nominapagodetalles}
                  setnominapagodetalles={setnominapagodetalles}
                  moneda={moneda}

                >
                </NominaPagos>
              } */}
            </>
          }

          {permiso([1,2,5]) && viewmainPanel === "alquileres" &&
            <Alquileres
              alquileresData={alquileresData}
              alquileresq={alquileresq}
              setalquileresq={setalquileresq}
              alquileresq_sucursal={alquileresq_sucursal}
              setalquileresq_sucursal={setalquileresq_sucursal}
              getAlquileres={getAlquileres}
              sucursales={sucursales}
              sendalquilerdesc={sendalquilerdesc}
              setsendalquilerdesc={setsendalquilerdesc}
              sendalquilermonto={sendalquilermonto}
              setsendalquilermonto={setsendalquilermonto}
              sendalquilersucursal={sendalquilersucursal}
              setsendalquilersucursal={setsendalquilersucursal}
              setNewAlquiler={setNewAlquiler}

              sendalquilerid={sendalquilerid}
              setsendalquilerid={setsendalquilerid}
              subviewAlquileres={subviewAlquileres}
              setsubviewAlquileres={setsubviewAlquileres}
              colorSucursal={colorSucursal}
              delAlquiler={delAlquiler}
              moneda={moneda}
            />
          }

          {permiso([1]) && viewmainPanel === "usuarios" &&
            <Usuarios
              usuarioNombre={usuarioNombre}
              setusuarioNombre={setusuarioNombre}
              usuarioUsuario={usuarioUsuario}
              setusuarioUsuario={setusuarioUsuario}
              usuarioRole={usuarioRole}
              setusuarioRole={setusuarioRole}
              usuarioClave={usuarioClave}
              setusuarioClave={setusuarioClave}
              usuarioArea={usuarioArea}
              setusuarioArea={setusuarioArea}
              indexSelectUsuarios={indexSelectUsuarios}
              setIndexSelectUsuarios={setIndexSelectUsuarios}
              qBuscarUsuario={qBuscarUsuario}
              setQBuscarUsuario={setQBuscarUsuario}
              delUsuario={delUsuario}
              usuariosData={usuariosData}
              addNewUsuario={addNewUsuario}
              getUsuarios={getUsuarios}
              sucursales={sucursales}
              usuarioId_sucursal={usuarioId_sucursal}
              setusuarioId_sucursal={setusuarioId_sucursal}
            />
          }
          {permiso([1,2]) && viewmainPanel === "creditos" &&
            <PorCobrar
              getsucursalDetallesData={getsucursalDetallesData}
              sucursalDetallesData={sucursalDetallesData}
              moneda={moneda}
              fechasMain1={fechasMain1}
              fechasMain2={fechasMain2}
              setfechasMain1={setfechasMain1}
              setfechasMain2={setfechasMain2}
              sucursalSelect={sucursalSelect}
              setsucursalSelect={setsucursalSelect}
              setsucursalDetallesData={setsucursalDetallesData}
              getSucursales={getSucursales}
              sucursales={sucursales}
              qestatusaprobaciocaja={qestatusaprobaciocaja}
              setqestatusaprobaciocaja={setqestatusaprobaciocaja}
              aprobarCreditoFun={aprobarCreditoFun}
              subviewpanelsucursales={subviewpanelsucursales}
              setsubviewpanelsucursales={setsubviewpanelsucursales}
              
              />
          }


          {permiso([1,2,9,10,13]) && viewmainPanel === "compras" &&
            <Compras
              permiso={permiso}
              setviewmainPanel={setviewmainPanel}
              viewmainPanel={viewmainPanel}
            />
          }
          {permiso([1,2,3,6,15]) && viewmainPanel === "auditoria" &&
            <Auditoria
            setbancosdata={setbancosdata}
            qfiltroaprotransf={qfiltroaprotransf}
            setqfiltroaprotransf={setqfiltroaprotransf}
            bancoqfiltroaprotransf={bancoqfiltroaprotransf}
            setbancoqfiltroaprotransf={setbancoqfiltroaprotransf}
            showallSelectAuditoria={showallSelectAuditoria}
            setshowallSelectAuditoria={setshowallSelectAuditoria}
            
            setshowimportliquidacion={setshowimportliquidacion}
            showimportliquidacion={showimportliquidacion}
            textimportliquidadcion={textimportliquidadcion}
            settextimportliquidadcion={settextimportliquidadcion}
            procesarImportTextliquidacion={procesarImportTextliquidacion}
            setdataimportliquidacion={setdataimportliquidacion}
            dataimportliquidacion={dataimportliquidacion}
            
            inpmontoNoreportado={inpmontoNoreportado}
            setinpmontoNoreportado={setinpmontoNoreportado}
            inpfechaNoreportado={inpfechaNoreportado}
            setinpfechaNoreportado={setinpfechaNoreportado}
            reportarMov={reportarMov}
            
            newmovnoreportadoref={newmovnoreportadoref}
            setnewmovnoreportadoref={setnewmovnoreportadoref}
            setshownewmovnoreportado={setshownewmovnoreportado}
            shownewmovnoreportado={shownewmovnoreportado}
            saveNewmovnoreportado={saveNewmovnoreportado}
            newmovnoreportadomonto={newmovnoreportadomonto}
            setnewmovnoreportadomonto={setnewmovnoreportadomonto}
            newmovnoreportadobanco={newmovnoreportadobanco}
            setnewmovnoreportadobanco={setnewmovnoreportadobanco}
            newmovnoreportadofecha={newmovnoreportadofecha}
            setnewmovnoreportadofecha={setnewmovnoreportadofecha}
            
            getAuditoriaEfec={getAuditoriaEfec}
            qauditoriaefectivo={qauditoriaefectivo}
            setqauditoriaefectivo={setqauditoriaefectivo}
            sucursalqauditoriaefectivo={sucursalqauditoriaefectivo}
            setsucursalqauditoriaefectivo={setsucursalqauditoriaefectivo}
            fechadesdeauditoriaefec={fechadesdeauditoriaefec}
            setfechadesdeauditoriaefec={setfechadesdeauditoriaefec}
            fechahastaauditoriaefec={fechahastaauditoriaefec}
            setfechahastaauditoriaefec={setfechahastaauditoriaefec}
            setqcajaauditoriaefectivo={setqcajaauditoriaefectivo}
            qcajaauditoriaefectivo={qcajaauditoriaefectivo}
            dataAuditoriaEfectivo={dataAuditoriaEfectivo}
            
            sucursalqcuadregeneral={sucursalqcuadregeneral}
            setsucursalqcuadregeneral={setsucursalqcuadregeneral}
            fechadesdeqcuadregeneral={fechadesdeqcuadregeneral}
            setfechadesdeqcuadregeneral={setfechadesdeqcuadregeneral}
            fechahastaqcuadregeneral={fechahastaqcuadregeneral}
            setfechahastaqcuadregeneral={setfechahastaqcuadregeneral}
            datacuadregeneral={datacuadregeneral}
            getCuadreGeneral={getCuadreGeneral}
            number={number}
            formatAmount={formatAmount}
            
            iscomisiongasto={iscomisiongasto}
            setiscomisiongasto={setiscomisiongasto}
            comisionpagomovilinterban={comisionpagomovilinterban}
            setcomisionpagomovilinterban={setcomisionpagomovilinterban}
            ingegreSelectAuditoria={ingegreSelectAuditoria}
            setingegreSelectAuditoria={setingegreSelectAuditoria}
            controlefecQDescripcion={controlefecQDescripcion}
            setcontrolefecQDescripcion={setcontrolefecQDescripcion}
            controlefecSelectCat={controlefecSelectCat}
            setcontrolefecSelectCat={setcontrolefecSelectCat}
            controlefecSelectGeneral={controlefecSelectGeneral}
            setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
            fechaAutoLiquidarTransferencia={fechaAutoLiquidarTransferencia}
            setfechaAutoLiquidarTransferencia={setfechaAutoLiquidarTransferencia}   
            bancoAutoLiquidarTransferencia={bancoAutoLiquidarTransferencia}
            setbancoAutoLiquidarTransferencia={setbancoAutoLiquidarTransferencia}
            
            autoliquidarTransferencia={autoliquidarTransferencia}
            categoriasCajas={categoriasCajas }
            permiso={permiso}
            getBancoName={getBancoName}
            setqestatusaprobaciocaja={setqestatusaprobaciocaja }
            sucursalDetallesData={sucursalDetallesData }
            getsucursalDetallesData={getsucursalDetallesData }
            getSucursales={getSucursales}
            setsubviewpanelsucursales={setsubviewpanelsucursales}
            subviewpanelsucursales={subviewpanelsucursales}
            fechasMain1={fechasMain1}
            fechasMain2={fechasMain2}
            sucursalSelect={sucursalSelect}
              setsucursalSelect={setsucursalSelect}
              qestatusaprobaciocaja={qestatusaprobaciocaja}
              setfechasMain1={setfechasMain1}
              setfechasMain2={setfechasMain2}
              aprobarTransferenciaFun={aprobarTransferenciaFun}
              subviewAuditoriaGeneral={subviewAuditoriaGeneral}
              setsubviewAuditoriaGeneral={setsubviewAuditoriaGeneral}
              changeBank={changeBank}
              reverserLiquidar={reverserLiquidar}
              colorFun={colorFun}
              colors={colors}
              colorSucursal={colorSucursal}
              subviewAuditoria={subviewAuditoria}
              setsubviewAuditoria={setsubviewAuditoria}
              selectxMovimientos={selectxMovimientos}
              setviewmainPanel={setviewmainPanel}
              viewmainPanel={viewmainPanel}
              opcionesMetodosPago={opcionesMetodosPago}
              setopcionesMetodosPago={setopcionesMetodosPago}
              bancosdata={bancosdata}
              tipoSelectAuditoria={tipoSelectAuditoria}
              settipoSelectAuditoria={settipoSelectAuditoria}
              fechaSelectAuditoria={fechaSelectAuditoria}
              setfechaSelectAuditoria={setfechaSelectAuditoria}
              fechaHastaSelectAuditoria={fechaHastaSelectAuditoria}
              setfechaHastaSelectAuditoria={setfechaHastaSelectAuditoria}
              bancoSelectAuditoria={bancoSelectAuditoria}
              setbancoSelectAuditoria={setbancoSelectAuditoria}
              sucursalSelectAuditoria={sucursalSelectAuditoria}
              setsucursalSelectAuditoria={setsucursalSelectAuditoria}
              qdescripcionbancosdata={qdescripcionbancosdata}
              setqdescripcionbancosdata={setqdescripcionbancosdata}
              SaldoInicialSelectAuditoria={SaldoInicialSelectAuditoria}
              setSaldoInicialSelectAuditoria={setSaldoInicialSelectAuditoria}
              SaldoActualSelectAuditoria={SaldoActualSelectAuditoria}
              setSaldoActualSelectAuditoria={setSaldoActualSelectAuditoria}
              getMetodosPago={getMetodosPago}
              getBancosData={getBancosData}
              colorsGastosCat={colorsGastosCat}
              getCatCajas={getCatCajas}
              sucursales={sucursales}

              cuentasPagosDescripcion={cuentasPagosDescripcion}
              setcuentasPagosDescripcion={setcuentasPagosDescripcion}
              cuentasPagosMetodoDestino={cuentasPagosMetodoDestino}
              setcuentasPagosMetodoDestino={setcuentasPagosMetodoDestino}
              cuentasPagosMonto={cuentasPagosMonto}
              setcuentasPagosMonto={setcuentasPagosMonto}
              cuentasPagosMetodo={cuentasPagosMetodo}
              setcuentasPagosMetodo={setcuentasPagosMetodo}
              cuentasPagosPuntooTranfe={cuentasPagosPuntooTranfe}
              setcuentasPagosPuntooTranfe={setcuentasPagosPuntooTranfe}
              cuentasPagosSucursal={cuentasPagosSucursal}
              setcuentasPagosSucursal={setcuentasPagosSucursal}
              cuentasPagosFecha={cuentasPagosFecha}
              setcuentasPagosFecha={setcuentasPagosFecha}
              sendMovimientoBanco={sendMovimientoBanco}

              cuentasPagoTipo={cuentasPagoTipo}
              setcuentasPagosTipo={setcuentasPagosTipo}
              cuentasPagosCategoria={cuentasPagosCategoria}
              setcuentasPagosCategoria={setcuentasPagosCategoria}
              moneda={moneda}
              movimientoAuditoria={movimientoAuditoria}
              setmovimientoAuditoria={setmovimientoAuditoria}
              selectTrLiquidar={selectTrLiquidar}
              setselectTrLiquidar={setselectTrLiquidar}
              inpmontoLiquidar={inpmontoLiquidar}
              setinpmontoLiquidar={setinpmontoLiquidar}
              inpfechaLiquidar={inpfechaLiquidar}
              setinpfechaLiquidar={setinpfechaLiquidar}
              liquidarMov={liquidarMov}
              orderAuditoria={orderAuditoria}
              setorderAuditoria={setorderAuditoria}
              orderColumnAuditoria={orderColumnAuditoria}
              setorderColumnAuditoria={setorderColumnAuditoria}
              selectConciliacion={selectConciliacion}
              saldoactualbancofecha={saldoactualbancofecha}
              setsaldoactualbancofecha={setsaldoactualbancofecha}
              sendsaldoactualbancofecha={sendsaldoactualbancofecha}
              selectConciliacionData={selectConciliacionData}
              setselectConciliacionData={setselectConciliacionData}

            />
          }

          {permiso([1,2,4,8,13]) && viewmainPanel === "efectivo" &&
            <Efectivo
              subviewpanelsucursales={subviewpanelsucursales}
              setsubviewpanelsucursales={setsubviewpanelsucursales}
              getsucursalDetallesData={getsucursalDetallesData}
              fechasMain1={fechasMain1}
              fechasMain2={fechasMain2}
              sucursalSelect={sucursalSelect}
              qestatusaprobaciocaja={qestatusaprobaciocaja}
              subViewCuentasxPagar={subViewCuentasxPagar}
              setsubViewCuentasxPagar={setsubViewCuentasxPagar}
            >
              

              {/* {subviewpanelsucursales === "aprobacioncajafuerte" &&
                
              } */}
              {subviewpanelsucursales === "cuentasporpagar" ?
                <>
                  {subViewCuentasxPagar === "detallado"?
                  <>
                    {cuentasporpagarDetallesView=="cuentas"?
                      <CuentasporpagarDetalles
                        setqcampoBusquedacuentasPorPagarDetalles={setqcampoBusquedacuentasPorPagarDetalles}
                        qcampoBusquedacuentasPorPagarDetalles={qcampoBusquedacuentasPorPagarDetalles}
                        setqinvertircuentasPorPagarDetalles={setqinvertircuentasPorPagarDetalles}
                        qinvertircuentasPorPagarDetalles={qinvertircuentasPorPagarDetalles}
                        conciliarCuenta={conciliarCuenta}
                        setdataselectFacts={setdataselectFacts}
                        dateFormat={dateFormat}
                        colorSucursal={colorSucursal}
                        returnCondicion={returnCondicion}
                        changeSucursal={changeSucursal}
                        abonarFactLote={abonarFactLote}
                        setsubviewAgregarFactPago={setsubviewAgregarFactPago}
                        abonarFact={abonarFact}
                        descuentoGeneralFats={descuentoGeneralFats}
                        setdescuentoGeneralFats={setdescuentoGeneralFats}
                        sendDescuentoGeneralFats={sendDescuentoGeneralFats}
                        dataselectFacts={dataselectFacts}
                        selectFacts={selectFacts}
                        sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
                        setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}

                        getSucursales={getSucursales}
                        sucursales={sucursales}
                        delCuentaPorPagar={delCuentaPorPagar}
                        changeAprobarFact={changeAprobarFact}
                        cuentaporpagarAprobado={cuentaporpagarAprobado}
                        setcuentaporpagarAprobado={setcuentaporpagarAprobado}
                        showImageFact={showImageFact}
                        cuentasporpagarDetallesView={cuentasporpagarDetallesView}
                        setcuentasporpagarDetallesView={setcuentasporpagarDetallesView}
                        setviewmainPanel={setviewmainPanel}
                        selectCuentaPorPagarId={selectCuentaPorPagarId}
                        setSelectCuentaPorPagarId={setSelectCuentaPorPagarId}
                        setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
                        qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
                        selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
                        proveedoresList={proveedoresList}
                        factSelectIndex={factSelectIndex}
                        moneda={moneda}

                        setqcuentasPorPagarTipoFact={setqcuentasPorPagarTipoFact}
                        qcuentasPorPagarTipoFact={qcuentasPorPagarTipoFact}
                        qCampocuentasPorPagarDetalles={qCampocuentasPorPagarDetalles}
                        setqCampocuentasPorPagarDetalles={setqCampocuentasPorPagarDetalles}
                        numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
                        setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
                        qFechaCampocuentasPorPagarDetalles={qFechaCampocuentasPorPagarDetalles}
                        setqFechaCampocuentasPorPagarDetalles={setqFechaCampocuentasPorPagarDetalles}
                        setfechacuentasPorPagarDetalles={setfechacuentasPorPagarDetalles}
                        fechacuentasPorPagarDetalles={fechacuentasPorPagarDetalles}
                        categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
                        setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
                        tipocuentasPorPagarDetalles={tipocuentasPorPagarDetalles}
                        settipocuentasPorPagarDetalles={settipocuentasPorPagarDetalles}
                        OrdercuentasPorPagarDetalles={OrdercuentasPorPagarDetalles}
                        setOrdercuentasPorPagarDetalles={setOrdercuentasPorPagarDetalles}

                        OrderFechacuentasPorPagarDetalles={OrderFechacuentasPorPagarDetalles}
                        setOrderFechacuentasPorPagarDetalles={setOrderFechacuentasPorPagarDetalles}
                        setSelectCuentaPorPagarDetalle={setSelectCuentaPorPagarDetalle}
                        SelectCuentaPorPagarDetalle={SelectCuentaPorPagarDetalle}
                        modeEditarFact={modeEditarFact}
                        setselectFactEdit={setselectFactEdit}
                        selectProveedorCxp={selectProveedorCxp}
                        setselectProveedorCxp={setselectProveedorCxp}
                        setcuentasPagosDescripcion={setcuentasPagosDescripcion}
                        setcuentasPagosMonto={setcuentasPagosMonto}
                        setselectFactPagoid={setselectFactPagoid}
                        setselectFactPagoid_sucursal={setselectFactPagoid_sucursal}
                        setcuentasPagosMetodo={setcuentasPagosMetodo}
                        setcuentasPagosFecha={setcuentasPagosFecha}
                        setselectAbonoFact={setselectAbonoFact}
                        subViewCuentasxPagar={subViewCuentasxPagar}
                        setsubViewCuentasxPagar={setsubViewCuentasxPagar}
                      />
                    :null}


                    {cuentasporpagarDetallesView=="pagos"?
                      <CuentasporpagarPago
                        setqcampoBusquedacuentasPorPagarDetalles={setqcampoBusquedacuentasPorPagarDetalles}
                        qcampoBusquedacuentasPorPagarDetalles={qcampoBusquedacuentasPorPagarDetalles}
                        setqinvertircuentasPorPagarDetalles={setqinvertircuentasPorPagarDetalles}
                        qinvertircuentasPorPagarDetalles={qinvertircuentasPorPagarDetalles}
                        returnCondicion={returnCondicion}
                        setSelectCuentaPorPagarDetalle={setSelectCuentaPorPagarDetalle}
                        showImageFact={showImageFact}

                        montobs1PagoFact={montobs1PagoFact}
                        setmontobs1PagoFact={setmontobs1PagoFact}
                        tasabs1PagoFact={tasabs1PagoFact}
                        settasabs1PagoFact={settasabs1PagoFact}
                        metodobs1PagoFact={metodobs1PagoFact}
                        setmetodobs1PagoFact={setmetodobs1PagoFact}
                        montobs2PagoFact={montobs2PagoFact}
                        setmontobs2PagoFact={setmontobs2PagoFact}
                        tasabs2PagoFact={tasabs2PagoFact}
                        settasabs2PagoFact={settasabs2PagoFact}
                        metodobs2PagoFact={metodobs2PagoFact}
                        setmetodobs2PagoFact={setmetodobs2PagoFact}
                        montobs3PagoFact={montobs3PagoFact}
                        setmontobs3PagoFact={setmontobs3PagoFact}
                        tasabs3PagoFact={tasabs3PagoFact}
                        settasabs3PagoFact={settasabs3PagoFact}
                        metodobs3PagoFact={metodobs3PagoFact}
                        setmetodobs3PagoFact={setmetodobs3PagoFact}
                        montobs4PagoFact={montobs4PagoFact}
                        setmontobs4PagoFact={setmontobs4PagoFact}
                        tasabs4PagoFact={tasabs4PagoFact}
                        settasabs4PagoFact={settasabs4PagoFact}
                        metodobs4PagoFact={metodobs4PagoFact}
                        setmetodobs4PagoFact={setmetodobs4PagoFact}
                        montobs5PagoFact={montobs5PagoFact}
                        setmontobs5PagoFact={setmontobs5PagoFact}
                        tasabs5PagoFact={tasabs5PagoFact}
                        settasabs5PagoFact={settasabs5PagoFact}
                        metodobs5PagoFact={metodobs5PagoFact}
                        setmetodobs5PagoFact={setmetodobs5PagoFact}
                        refbs1PagoFact={refbs1PagoFact}                        
                        setrefbs1PagoFact={setrefbs1PagoFact}
                        refbs2PagoFact={refbs2PagoFact}                        
                        setrefbs2PagoFact={setrefbs2PagoFact}
                        refbs3PagoFact={refbs3PagoFact}                        
                        setrefbs3PagoFact={setrefbs3PagoFact}
                        refbs4PagoFact={refbs4PagoFact}                        
                        setrefbs4PagoFact={setrefbs4PagoFact}
                        refbs5PagoFact={refbs5PagoFact}                        
                        setrefbs5PagoFact={setrefbs5PagoFact}

                        qcuentasPorPagarTipoFact={qcuentasPorPagarTipoFact}
                        cuentaporpagarAprobado={cuentaporpagarAprobado}
                        setcuentaporpagarAprobado={setcuentaporpagarAprobado}
                        setselectProveedorCxp={setselectProveedorCxp}
                        selectProveedorCxp={selectProveedorCxp}
                        sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
                        sucursales={sucursales}
                        delItemSelectAbonoFact={delItemSelectAbonoFact}
                        setqcuentasPorPagarTipoFact={setqcuentasPorPagarTipoFact }
                        setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}
                        selectFactPagoid={selectFactPagoid}
                        getProveedores={getProveedores}
                        proveedoresList={proveedoresList}
                        selectAbonoFact={selectAbonoFact}
                        setselectAbonoFact={setselectAbonoFact}
                        setInputAbonoFact={setInputAbonoFact}
                        sendPagoCuentaPorPagar={sendPagoCuentaPorPagar}
                        cuentasporpagarDetallesView={cuentasporpagarDetallesView}
                        setcuentasporpagarDetallesView={setcuentasporpagarDetallesView}
                        cuentasPagosDescripcion={cuentasPagosDescripcion}
                        setcuentasPagosDescripcion={setcuentasPagosDescripcion}
                        cuentasPagosMonto={cuentasPagosMonto}
                        setcuentasPagosMonto={setcuentasPagosMonto}
                        cuentasPagosMetodo={cuentasPagosMetodo}
                        setcuentasPagosMetodo={setcuentasPagosMetodo}
                        cuentasPagosFecha={cuentasPagosFecha}
                        setcuentasPagosFecha={setcuentasPagosFecha}
                        opcionesMetodosPago={opcionesMetodosPago}
                        number={number}

                        selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
                        setqCampocuentasPorPagarDetalles={setqCampocuentasPorPagarDetalles}
                        OrdercuentasPorPagarDetalles={OrdercuentasPorPagarDetalles}
                        setOrdercuentasPorPagarDetalles={setOrdercuentasPorPagarDetalles}
                        qCampocuentasPorPagarDetalles={qCampocuentasPorPagarDetalles}
                        numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
                        setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
                        qFechaCampocuentasPorPagarDetalles={qFechaCampocuentasPorPagarDetalles}
                        setqFechaCampocuentasPorPagarDetalles={setqFechaCampocuentasPorPagarDetalles}
                        OrderFechacuentasPorPagarDetalles={OrderFechacuentasPorPagarDetalles}
                        setOrderFechacuentasPorPagarDetalles={setOrderFechacuentasPorPagarDetalles}
                        fechacuentasPorPagarDetalles={fechacuentasPorPagarDetalles}
                        setfechacuentasPorPagarDetalles={setfechacuentasPorPagarDetalles}
                        categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
                        setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
                        tipocuentasPorPagarDetalles={tipocuentasPorPagarDetalles}
                        settipocuentasPorPagarDetalles={settipocuentasPorPagarDetalles}
                        selectCuentaPorPagarId={selectCuentaPorPagarId}
                        setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
                        qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
                        selectFacturaSetPago={selectFacturaSetPago}
                        selectFactPagoArr={selectFactPagoArr}
                        setselectFactPagoArr={setselectFactPagoArr}

                        setsubviewAgregarFactPago={setsubviewAgregarFactPago}
                        subviewAgregarFactPago={subviewAgregarFactPago}
                        

                        moneda={moneda}

                        setnewfactid_proveedor={setnewfactid_proveedor}
                        newfactid_proveedor={newfactid_proveedor}
                        setnewfactnumfact={setnewfactnumfact}
                        newfactnumfact={newfactnumfact}
                        setnewfactnumnota={setnewfactnumnota}
                        newfactnumnota={newfactnumnota}
                        setnewfactdescripcion={setnewfactdescripcion}
                        setnewfactsucursal={setnewfactsucursal}
                        newfactdescripcion={newfactdescripcion}
                        newfactsucursal={newfactsucursal}
                        setnewfactsubtotal={setnewfactsubtotal}
                        newfactsubtotal={newfactsubtotal}
                        setnewfactdescuento={setnewfactdescuento}
                        newfactdescuento={newfactdescuento}
                        setnewfactmonto_exento={setnewfactmonto_exento}
                        newfactmonto_exento={newfactmonto_exento}
                        setnewfactmonto_gravable={setnewfactmonto_gravable}
                        newfactmonto_gravable={newfactmonto_gravable}
                        setnewfactiva={setnewfactiva}
                        newfactiva={newfactiva}
                        setnewfactmonto={setnewfactmonto}
                        newfactmonto={newfactmonto}
                        setnewfactfechaemision={setnewfactfechaemision}
                        newfactfechaemision={newfactfechaemision}
                        setnewfactfechavencimiento={setnewfactfechavencimiento}
                        newfactfechavencimiento={newfactfechavencimiento}
                        setnewfactfecharecepcion={setnewfactfecharecepcion}
                        newfactfecharecepcion={newfactfecharecepcion}
                        setnewfactnota={setnewfactnota}
                        newfactnota={newfactnota}
                        setnewfacttipo={setnewfacttipo}
                        newfacttipo={newfacttipo}
                        setnewfactfrecuencia={setnewfactfrecuencia}
                        newfactfrecuencia={newfactfrecuencia}

                        setselectFactEdit={setselectFactEdit}
                        selectFactEdit={selectFactEdit}
                        saveNewFact={saveNewFact}

                      />
                    :null}
                  </>
                  :null}
                  {subViewCuentasxPagar === "proveedor"?
                    <Cuentasporpagar
                      selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
                      subViewCuentasxPagar={subViewCuentasxPagar}
                      setsubViewCuentasxPagar={setsubViewCuentasxPagar}
                      setviewmainPanel={setviewmainPanel}
                      moneda={moneda}
                      getsucursalDetallesData={getsucursalDetallesData}
                      setsucursalDetallesData={setsucursalDetallesData}
                      sucursalDetallesData={sucursalDetallesData}
                      getSucursales={getSucursales}
                      sucursales={sucursales}
                      number={number}
                      qcuentasPorPagar={qcuentasPorPagar}
                      setqcuentasPorPagar={setqcuentasPorPagar}
                      selectCuentaPorPagarId={selectCuentaPorPagarId}
                      setSelectCuentaPorPagarId={setSelectCuentaPorPagarId}
                      selectProveedorCxp={selectProveedorCxp}
                      setselectProveedorCxp={setselectProveedorCxp}
                    />
                  :null}

                  {
                    subViewCuentasxPagar === "disponible"?
                    <EfectivoDisponibleSucursales
                      formatAmount={formatAmount}
                      efectivoDisponibleSucursalesData={efectivoDisponibleSucursalesData}
                      setefectivoDisponibleSucursalesData={setefectivoDisponibleSucursalesData}
                      getDisponibleEfectivoSucursal={getDisponibleEfectivoSucursal}
                      colorSucursal={colorSucursal}
                      moneda={moneda}

                      datacajamatriz={datacajamatriz}
                      colorsGastosCat={colorsGastosCat}
                      depositarmatrizalbanco={depositarmatrizalbanco}
                      getCajaMatriz={getCajaMatriz}
                      qcajamatriz={qcajamatriz}
                      setqcajamatriz={setqcajamatriz}
                      sucursalqcajamatriz={sucursalqcajamatriz}
                      setsucursalqcajamatriz={setsucursalqcajamatriz}
                      fechadesdecajamatriz={fechadesdecajamatriz}
                      setfechadesdecajamatriz={setfechadesdecajamatriz}
                      fechahastacajamatriz={fechahastacajamatriz}
                      setfechahastacajamatriz={setfechahastacajamatriz}
                      sucursales={sucursales}

                      bancodepositobanco={bancodepositobanco}
                      setbancodepositobanco={setbancodepositobanco}
                      fechadepositobanco={fechadepositobanco}
                      setfechadepositobanco={setfechadepositobanco}
                      selectdepositobanco={selectdepositobanco}
                      setselectdepositobanco={setselectdepositobanco}
                      opcionesMetodosPago={opcionesMetodosPago}

                      number={number}

                      setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
                      getAlquileres={getAlquileres}
                      getSucursales={getSucursales}
                      controlefecQ={controlefecQ}
                      setcontrolefecQ={setcontrolefecQ}
                      controlefecQDesde={controlefecQDesde}
                      setcontrolefecQDesde={setcontrolefecQDesde}
                      controlefecQHasta={controlefecQHasta}
                      setcontrolefecQHasta={setcontrolefecQHasta}
                      controlefecData={controlefecData}
                      controlefecSelectGeneral={controlefecSelectGeneral}
                      controlefecNewConcepto={controlefecNewConcepto}
                      setcontrolefecNewConcepto={setcontrolefecNewConcepto}
                      controlefecNewFecha={controlefecNewFecha}
                      setcontrolefecNewFecha={setcontrolefecNewFecha}
                      

                      controlefecNewCategoria={controlefecNewCategoria}
                      setcontrolefecNewCategoria={setcontrolefecNewCategoria}
                      controlefecNewMonto={controlefecNewMonto}
                      setcontrolefecNewMonto={setcontrolefecNewMonto}
                      getControlEfec={getControlEfec}
                      setControlEfec={setControlEfec}
                      setcontrolefecQCategoria={setcontrolefecQCategoria}
                      controlefecQCategoria={controlefecQCategoria}
                      controlefecNewMontoMoneda={controlefecNewMontoMoneda}
                      setcontrolefecNewMontoMoneda={setcontrolefecNewMontoMoneda}
                      categoriasCajas={categoriasCajas}
                      getcatsCajas={getCatCajas}
                      delCaja={delCaja}
                      personalNomina={nominaData}
                      getNomina={getPersonalNomina}
                      setopenModalNuevoEfectivo={setopenModalNuevoEfectivo}
                      openModalNuevoEfectivo={openModalNuevoEfectivo}
                      verificarMovPenControlEfec={verificarMovPenControlEfec}
                      verificarMovPenControlEfecTRANFTRABAJADOR={verificarMovPenControlEfecTRANFTRABAJADOR}
                      allProveedoresCentral={proveedoresList}
                      getAllProveedores={getProveedores}
                      alquileresData={alquileresData}
                      sucursalesCentral={sucursales}
                      transferirpedidoa={transferirpedidoa}
                      settransferirpedidoa={settransferirpedidoa}
                      reversarMovPendientes={reversarMovPendientes}
                      aprobarRecepcionCaja={aprobarRecepcionCaja}
                      dolar={dolar}
                      peso={peso}
                    />:null
                  }
                </>
              :null
              }

            </Efectivo>
          }
          {permiso([1,9,10]) && viewmainPanel === "comprascargarfactsfisicas" &&
            <ComprasCargarFactsFiscas
              numfact_select_imagen={numfact_select_imagen}
              factInpImagen={factInpImagen}              
              setfactInpImagen={setfactInpImagen}
              factInpProveedor={factInpProveedor}              
              setfactInpProveedor={setfactInpProveedor}
              factNumfact={factNumfact}              
              setfactNumfact={setfactNumfact}
              sendComprasFats={sendComprasFats}              
              proveedoresList={proveedoresList}
              sucursales={sucursales}
            />
          }
          {permiso([1,10]) && viewmainPanel === "comprasmodalselectfactsfisicas" &&
              <Comprasmodalselectfactfisicas
                numfact_select_imagen={numfact_select_imagen}
                seleccionarFilecxpFun={seleccionarFilecxpFun}
                colorSucursal={colorSucursal}
                setviewmainPanel={setviewmainPanel}
                modalfilesexplorercxp={modalfilesexplorercxp}
                setmodalfilesexplorercxp={setmodalfilesexplorercxp}
                selectFilecxp={selectFilecxp}
                setselectFilecxp={setselectFilecxp}
                delFilescxp={delFilescxp}
                getFilescxp={getFilescxp}
                showFilescxp={showFilescxp}
                dataFilescxp={dataFilescxp}
                setdataFilescxp={setdataFilescxp}

                qnumfactFilescxp={qnumfactFilescxp}
                setqnumfactFilescxp={setqnumfactFilescxp}

                qid_proveedorFilescxp={qid_proveedorFilescxp}
                setqid_proveedorFilescxp={setqid_proveedorFilescxp}

                qid_sucursalFilescxp={qid_sucursalFilescxp}
                setqid_sucursalFilescxp={setqid_sucursalFilescxp}

                qfechaFilescxp={qfechaFilescxp}
                setqfechaFilescxp={setqfechaFilescxp}
                proveedoresList={proveedoresList}
                sucursales={sucursales}
              />
          }
          
          {permiso([1,2,10,13]) && viewmainPanel === "cargarfactsdigitales" &&
          <>
              <Comprasmenufactsdigital 
                viewmainPanel={viewmainPanel}
                setviewmainPanel={setviewmainPanel}
                permiso={permiso}
              />
              <ComprasCargarFactsDigitales
                changeAprobarFact={changeAprobarFact}
                setfactInpImagen={setfactInpImagen}
                numfact_select_imagen={numfact_select_imagen}
                showFilescxp={showFilescxp}
                dataFilescxp={dataFilescxp}
                selectFilecxp={selectFilecxp}
                setselectFilecxp={setselectFilecxp}
                setviewmainPanel={setviewmainPanel}
                number={number}
                saveFacturaLote={saveFacturaLote}
                handleFacturaxLotes={handleFacturaxLotes}
                selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
                cuentaporpagarAprobado={cuentaporpagarAprobado}
                setcuentaporpagarAprobado={setcuentaporpagarAprobado}
                setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
                qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
                setselectProveedorCxp={setselectProveedorCxp}
                selectProveedorCxp={selectProveedorCxp}
                proveedoresList={proveedoresList}
                sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
                setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}
                sucursales={sucursales}
                categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
                setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
                qCampocuentasPorPagarDetalles={qCampocuentasPorPagarDetalles}
                setOrdercuentasPorPagarDetalles={setOrdercuentasPorPagarDetalles}
                setqCampocuentasPorPagarDetalles={setqCampocuentasPorPagarDetalles}
                numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
                setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
                selectCuentaPorPagarId={selectCuentaPorPagarId}
                qcuentasPorPagarTipoFact={qcuentasPorPagarTipoFact}
                dateFormat={dateFormat}
                returnCondicion={returnCondicion}
                colorSucursal={colorSucursal}
                moneda={moneda}
              />
          </>
          }

          {permiso([1,2,10]) && viewmainPanel === "distribuirfacts" &&
          <>
            <Comprasmenufactsdigital 
              viewmainPanel={viewmainPanel}
              setviewmainPanel={setviewmainPanel}
              permiso={permiso}
            />
            <ComprasDistribuirFacts
              selectFactToDistribuirFun={selectFactToDistribuirFun}
              setqcampoBusquedacuentasPorPagarDetalles={setqcampoBusquedacuentasPorPagarDetalles}
              qcampoBusquedacuentasPorPagarDetalles={qcampoBusquedacuentasPorPagarDetalles}
              setqinvertircuentasPorPagarDetalles={setqinvertircuentasPorPagarDetalles}
              qinvertircuentasPorPagarDetalles={qinvertircuentasPorPagarDetalles}
              autorepartircantidades={autorepartircantidades}
              number={number}
              changeInputDistribuirpedido={changeInputDistribuirpedido}
              subviewDistribuir={subviewDistribuir}
              setsubviewDistribuir={setsubviewDistribuir}
              listdistribucionselect={listdistribucionselect}
              setlistdistribucionselect={setlistdistribucionselect}
              distribucionSelectSucursal={distribucionSelectSucursal}
              setdistribucionSelectSucursal={setdistribucionSelectSucursal}
              addlistdistribucionselect={addlistdistribucionselect}
              dellistdistribucionselect={dellistdistribucionselect}
              sendlistdistribucionselect={sendlistdistribucionselect}
              setfacturaSelectAddItems={setfacturaSelectAddItems}
              facturaSelectAddItems={facturaSelectAddItems}
              selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
              cuentaporpagarAprobado={cuentaporpagarAprobado}
              setcuentaporpagarAprobado={setcuentaporpagarAprobado}
              setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
              qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
              setselectProveedorCxp={setselectProveedorCxp}
              selectProveedorCxp={selectProveedorCxp}
              proveedoresList={proveedoresList}
              sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
              setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}
              sucursales={sucursales}
              categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
              setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
              qCampocuentasPorPagarDetalles={qCampocuentasPorPagarDetalles}
              setOrdercuentasPorPagarDetalles={setOrdercuentasPorPagarDetalles}
              setqCampocuentasPorPagarDetalles={setqCampocuentasPorPagarDetalles}
              numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
              setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
              selectCuentaPorPagarId={selectCuentaPorPagarId}
              returnCondicion={returnCondicion}
              colorSucursal={colorSucursal}
              moneda={moneda}
            />
          </>
          }
          
           {permiso([1,2,10]) && viewmainPanel === "procesarfactsdigitales" &&
            <>
              <Comprasmenufactsdigital 
                viewmainPanel={viewmainPanel}
                setviewmainPanel={setviewmainPanel}
                permiso={permiso}
              />
            </>
          }
          
          {permiso([1,2,10]) && viewmainPanel === "pedidos" &&
          <>
            <Comprasmenufactsdigital 
              setqcampoBusquedacuentasPorPagarDetalles={setqcampoBusquedacuentasPorPagarDetalles}
              qcampoBusquedacuentasPorPagarDetalles={qcampoBusquedacuentasPorPagarDetalles}
              setqinvertircuentasPorPagarDetalles={setqinvertircuentasPorPagarDetalles}
              qinvertircuentasPorPagarDetalles={qinvertircuentasPorPagarDetalles}
              viewmainPanel={viewmainPanel}
              setviewmainPanel={setviewmainPanel}
              permiso={permiso}
            />
            <Pedidos 
              eliminarVinculoCentral={eliminarVinculoCentral}
              qnovedadesPedidodos={qnovedadesPedidodos}
              setqnovedadesPedidodos={setqnovedadesPedidodos}
              novedadesPedidosData={novedadesPedidosData}
              getNovedadesPedidosData={getNovedadesPedidosData}
              revolverNovedadItemTrans={revolverNovedadItemTrans}
              qpedidosucursaldestino={qpedidosucursaldestino}
              setqpedidosucursaldestino={setqpedidosucursaldestino}
              inputBuscarInventario={inputBuscarInventario}
              qBuscarInventario={qBuscarInventario}
              setQBuscarInventario={setQBuscarInventario}
              Invnum={Invnum}
              setInvnum={setInvnum}
              InvorderColumn={InvorderColumn}
              setInvorderColumn={setInvorderColumn}
              InvorderBy={InvorderBy}
              setInvorderBy={setInvorderBy}
              productosInventario={productosInventario}
              indexSelectCarrito={indexSelectCarrito}
              setindexSelectCarrito={setindexSelectCarrito}
              showCantidadCarritoFun={showCantidadCarritoFun}
              showCantidadCarrito={showCantidadCarrito}
              setshowCantidadCarrito={setshowCantidadCarrito}
              sucursales={sucursales}
              ctSucursales={ctSucursales}
              setctSucursales={setctSucursales}
              number={number}
              setCarrito={setCarrito}
              pedidoList={pedidoList}
              id_pedido={id_pedido}
              setid_pedido={setid_pedido}
              qpedido={qpedido}
              setqpedido={setqpedido}
              qpedidosucursal={qpedidosucursal}
              setqpedidosucursal={setqpedidosucursal}
              qpedidoDateFrom={qpedidoDateFrom}
              setqpedidoDateFrom={setqpedidoDateFrom}
              qpedidoDateTo={qpedidoDateTo}
              setqpedidoDateTo={setqpedidoDateTo}
              qpedidoOrderBy={qpedidoOrderBy}
              setqpedidoOrderBy={setqpedidoOrderBy}
              qpedidoOrderByDescAsc={qpedidoOrderByDescAsc}
              setqpedidoOrderByDescAsc={setqpedidoOrderByDescAsc}
              pedidos={pedidos}
              setpedidos={setpedidos}
              pedidoData={pedidoData}
              setpedidoData={setpedidoData}
              qestadopedido={qestadopedido}
              setqestadopedido={setqestadopedido}
              getPedidos={getPedidos}
              delPedido={delPedido}
              selectPedido={selectPedido}
              moneda={moneda}
              setDelCarrito={setDelCarrito}
              setCtCarrito={setCtCarrito}
              setProdCarritoInterno={setProdCarritoInterno}
              sendPedidoSucursal={sendPedidoSucursal}
              showPedidoBarras={showPedidoBarras}
              aprobarRevisionPedido={aprobarRevisionPedido}
            >
              
            </Pedidos>
          </>
          }

          {permiso([1,2,10,14]) && viewmainPanel === "dici" &&
          <>
            <Inventario
              delVinculoSucursal={delVinculoSucursal}
              id_sucursal_select_internoModal={id_sucursal_select_internoModal}
              setid_sucursal_select_internoModal={setid_sucursal_select_internoModal}
              productosInventarioModal={productosInventarioModal}
              setproductosInventarioModal={setproductosInventarioModal}
              InvnumModal={InvnumModal}
              setInvnumModal={setInvnumModal}
              qBuscarInventarioModal={qBuscarInventarioModal}
              setqBuscarInventarioModal={setqBuscarInventarioModal}
              InvorderColumnModal={InvorderColumnModal}
              setInvorderColumnModal={setInvorderColumnModal}
              InvorderByModal={InvorderByModal}
              setInvorderByModal={setInvorderByModal}
              buscarInventarioModal={buscarInventarioModal}

              inputbuscarcentralforvincular={inputbuscarcentralforvincular}
              modalmovilx={modalmovilx}
              modalmovily={modalmovily}
              setmodalmovilshow={setmodalmovilshow}
              modalmovilshow={modalmovilshow}
              modalmovilRef={modalmovilRef}
              linkproductocentralsucursal={linkproductocentralsucursal}
              idselectproductoinsucursalforvicular={idselectproductoinsucursalforvicular}

              openVincularSucursalwithCentral={openVincularSucursalwithCentral}
              sendTareaRemoverDuplicado={sendTareaRemoverDuplicado}
              listselectEliminarDuplicados={listselectEliminarDuplicados}
              selectEliminarDuplicados={selectEliminarDuplicados}
              number={number}
              setqTareaPendienteFecha={setqTareaPendienteFecha}
              qTareaPendienteFecha={qTareaPendienteFecha}
              qTareaPendienteSucursal={qTareaPendienteSucursal}
              setqTareaPendienteSucursal={setqTareaPendienteSucursal}
              getTareasPendientes={getTareasPendientes}
              tareasPendientesData={tareasPendientesData}
              qTareaPendienteEstado={qTareaPendienteEstado}
              setqTareaPendienteEstado={setqTareaPendienteEstado}
              qTareaPendienteNum={qTareaPendienteNum}
              setqTareaPendienteNum={setqTareaPendienteNum}

              garantiasData={garantiasData}
              garantiaq={garantiaq}
              setgarantiaq={setgarantiaq}
              garantiaqsucursal={garantiaqsucursal}
              setgarantiaqsucursal={setgarantiaqsucursal}
              getGarantias={getGarantias}

              dataPedidoAnulacionAprobacion={dataPedidoAnulacionAprobacion}
              qdesdePedidoAnulacionAprobacion={qdesdePedidoAnulacionAprobacion}
              setqdesdePedidoAnulacionAprobacion={setqdesdePedidoAnulacionAprobacion}
              qhastaPedidoAnulacionAprobacion={qhastaPedidoAnulacionAprobacion}
              setqhastaPedidoAnulacionAprobacion={setqhastaPedidoAnulacionAprobacion}
              qnumPedidoAnulacionAprobacion={qnumPedidoAnulacionAprobacion}
              setqnumPedidoAnulacionAprobacion={setqnumPedidoAnulacionAprobacion}
              qestatusPedidoAnulacionAprobacion={qestatusPedidoAnulacionAprobacion}
              setqestatusPedidoAnulacionAprobacion={setqestatusPedidoAnulacionAprobacion}
              getAprobacionPedidoAnulacion={getAprobacionPedidoAnulacion}
              setAprobacionPedidoAnulacion={setAprobacionPedidoAnulacion}

              sucursalPedidoAnulacionAprobacion={sucursalPedidoAnulacionAprobacion}
              setsucursalPedidoAnulacionAprobacion={setsucursalPedidoAnulacionAprobacion}
              moneda={moneda}

              setInvnum={setInvnum}
              Invnum={Invnum}
              InvorderColumn={InvorderColumn}
              setInvorderColumn={setInvorderColumn}
              InvorderBy={InvorderBy}
              setInvorderBy={setInvorderBy}
              modNombres={modNombres}
              newNombres={newNombres}
              buscarNombres={buscarNombres}
              qnombres={qnombres}
              setqnombres={setqnombres}
              qtiponombres={qtiponombres}
              setqtiponombres={setqtiponombres}
              datanombres={datanombres}
              sameCatValue={sameCatValue}
              selectcampobusquedaestadistica={selectcampobusquedaestadistica}
              setselectcampobusquedaestadistica={setselectcampobusquedaestadistica}
              dataCamposBusquedaEstadisticas={dataCamposBusquedaEstadisticas}
              selectvalorcampobusquedaestadistica={selectvalorcampobusquedaestadistica}
              setselectvalorcampobusquedaestadistica={setselectvalorcampobusquedaestadistica}
              agregarCampoBusquedaEstadisticas={agregarCampoBusquedaEstadisticas}
              selectsucursalbusquedaestadistica={selectsucursalbusquedaestadistica}
              setselectsucursalbusquedaestadistica={setselectsucursalbusquedaestadistica}
              agregarSucursalBusquedaEstadisticas={agregarSucursalBusquedaEstadisticas}
              camposAgregadosBusquedaEstadisticas={camposAgregadosBusquedaEstadisticas}
              sucursalesAgregadasBusquedaEstadisticas={sucursalesAgregadasBusquedaEstadisticas}

              setcamposAgregadosBusquedaEstadisticas={setcamposAgregadosBusquedaEstadisticas}
              setsucursalesAgregadasBusquedaEstadisticas={setsucursalesAgregadasBusquedaEstadisticas}

              type={type}
              buscarInventario={buscarInventario}
              qBuscarInventario={qBuscarInventario}
              setQBuscarInventario={setQBuscarInventario}
              qBuscarInventarioSucursal={qBuscarInventarioSucursal}
              setqBuscarInventarioSucursal={setqBuscarInventarioSucursal}
              productosInventario={productosInventario}
              selectIdVinculacion={selectIdVinculacion} 
              setselectIdVinculacion={setselectIdVinculacion}
              qvinculacion1={qvinculacion1} 
              setqvinculacion1={setqvinculacion1}
              qvinculacion2={qvinculacion2} 
              setqvinculacion2={setqvinculacion2}
              qvinculacion3={qvinculacion3} 
              setqvinculacion3={setqvinculacion3}
              qvinculacion4={qvinculacion4} 
              setqvinculacion4={setqvinculacion4}
              qvinculacionmarca={qvinculacionmarca} 
              setqvinculacionmarca={setqvinculacionmarca}
              datavinculacion1={datavinculacion1} 
              setdatavinculacion1={setdatavinculacion1}
              datavinculacion2={datavinculacion2} 
              setdatavinculacion2={setdatavinculacion2}
              datavinculacion3={datavinculacion3} 
              setdatavinculacion3={setdatavinculacion3}
              datavinculacion4={datavinculacion4} 
              setdatavinculacion4={setdatavinculacion4}
              datavinculacionmarca={datavinculacionmarca} 
              setdatavinculacionmarca={setdatavinculacionmarca}
              inputselectvinculacion1={inputselectvinculacion1} 
              setinputselectvinculacion1={setinputselectvinculacion1}
              inputselectvinculacion2={inputselectvinculacion2} 
              setinputselectvinculacion2={setinputselectvinculacion2}
              inputselectvinculacion3={inputselectvinculacion3} 
              setinputselectvinculacion3={setinputselectvinculacion3}
              inputselectvinculacion4={inputselectvinculacion4} 
              setinputselectvinculacion4={setinputselectvinculacion4}
              inputselectvinculacionmarca={inputselectvinculacionmarca} 
              setinputselectvinculacionmarca={setinputselectvinculacionmarca}
              inputselectvinculacion1General={inputselectvinculacion1General} 
              setinputselectvinculacion1General={setinputselectvinculacion1General}
              inputselectvinculacion2General={inputselectvinculacion2General} 
              setinputselectvinculacion2General={setinputselectvinculacion2General}
              inputselectvinculacion3General={inputselectvinculacion3General} 
              setinputselectvinculacion3General={setinputselectvinculacion3General}
              inputselectvinculacion4General={inputselectvinculacion4General} 
              setinputselectvinculacion4General={setinputselectvinculacion4General}
              inputselectvinculacionmarcaGeneral={inputselectvinculacionmarcaGeneral} 
              setinputselectvinculacionmarcaGeneral={setinputselectvinculacionmarcaGeneral}

              inputselectvinculacion5={inputselectvinculacion5}
              setinputselectvinculacion5={setinputselectvinculacion5}
              inputselectvinculacioncat={inputselectvinculacioncat}
              setinputselectvinculacioncat={setinputselectvinculacioncat}
              inputselectvinculacioncatesp={inputselectvinculacioncatesp}
              setinputselectvinculacioncatesp={setinputselectvinculacioncatesp}
              inputselectvinculacionproveedor={inputselectvinculacionproveedor}
              setinputselectvinculacionproveedor={setinputselectvinculacionproveedor}
              inputselectvinculacionmaxct={inputselectvinculacionmaxct}
              setinputselectvinculacionmaxct={setinputselectvinculacionmaxct}
              inputselectvinculacionminct={inputselectvinculacionminct}
              setinputselectvinculacionminct={setinputselectvinculacionminct}
              inputselectvinculacion5General={inputselectvinculacion5General}
              setinputselectvinculacion5General={setinputselectvinculacion5General}
              inputselectvinculacioncatGeneral={inputselectvinculacioncatGeneral}
              setinputselectvinculacioncatGeneral={setinputselectvinculacioncatGeneral}
              inputselectvinculacioncatespGeneral={inputselectvinculacioncatespGeneral}
              setinputselectvinculacioncatespGeneral={setinputselectvinculacioncatespGeneral}
              inputselectvinculacionproveedorGeneral={inputselectvinculacionproveedorGeneral}
              setinputselectvinculacionproveedorGeneral={setinputselectvinculacionproveedorGeneral}
              inputselectvinculacionmaxctGeneral={inputselectvinculacionmaxctGeneral}
              setinputselectvinculacionmaxctGeneral={setinputselectvinculacionmaxctGeneral}
              inputselectvinculacionminctGeneral={inputselectvinculacionminctGeneral}
              setinputselectvinculacionminctGeneral={setinputselectvinculacionminctGeneral}

              qvinculacion5={qvinculacion5} 
              setqvinculacion5={setqvinculacion5}
              qvinculaciocat={qvinculaciocat} 
              setqvinculaciocat={setqvinculaciocat}
              qvinculaciocatesp={qvinculaciocatesp} 
              setqvinculaciocatesp={setqvinculaciocatesp}
              qvinculacioproveedor={qvinculacioproveedor} 
              setqvinculacioproveedor={setqvinculacioproveedor}
              qvinculaciomaxct={qvinculaciomaxct} 
              setqvinculaciomaxct={setqvinculaciomaxct}
              qvinculaciominct={qvinculaciominct} 
              setqvinculaciominct={setqvinculaciominct}
              qvinculacion5General={qvinculacion5General} 
              setqvinculacion5General={setqvinculacion5General}
              qvinculaciocatGeneral={qvinculaciocatGeneral} 
              setqvinculaciocatGeneral={setqvinculaciocatGeneral}
              qvinculaciocatespGeneral={qvinculaciocatespGeneral} 
              setqvinculaciocatespGeneral={setqvinculaciocatespGeneral}
              qvinculacioproveedorGeneral={qvinculacioproveedorGeneral} 
              setqvinculacioproveedorGeneral={setqvinculacioproveedorGeneral}
              qvinculaciomaxctGeneral={qvinculaciomaxctGeneral} 
              setqvinculaciomaxctGeneral={setqvinculaciomaxctGeneral}
              qvinculaciominctGeneral={qvinculaciominctGeneral} 
              setqvinculaciominctGeneral={setqvinculaciominctGeneral}
              datavinculacion5={datavinculacion5} 
              setdatavinculacion5={setdatavinculacion5}
              datavinculaciocat={datavinculaciocat} 
              setdatavinculaciocat={setdatavinculaciocat}
              datavinculaciocatesp={datavinculaciocatesp} 
              setdatavinculaciocatesp={setdatavinculaciocatesp}
              datavinculacioproveedor={datavinculacioproveedor} 
              setdatavinculacioproveedor={setdatavinculacioproveedor}
              datavinculaciomaxct={datavinculaciomaxct} 
              setdatavinculaciomaxct={setdatavinculaciomaxct}
              datavinculaciominct={datavinculaciominct} 
              setdatavinculaciominct={setdatavinculaciominct}
              newNombre5={newNombre5}
              setnewNombre5={setnewNombre5}
              newNombrecat={newNombrecat}
              setnewNombrecat={setnewNombrecat}
              newNombrecatesp={newNombrecatesp}
              setnewNombrecatesp={setnewNombrecatesp}
              newNombreproveedor={newNombreproveedor}
              setnewNombreproveedor={setnewNombreproveedor}
              newNombremaxct={newNombremaxct}
              setnewNombremaxct={setnewNombremaxct}
              newNombreminct={newNombreminct}
              setnewNombreminct={setnewNombreminct}

              getDatinputSelectVinculacion={getDatinputSelectVinculacion}
              saveCuatroNombres={saveCuatroNombres}

              changeInventarioModificarDici={changeInventarioModificarDici}
              guardarmodificarInventarioDici={guardarmodificarInventarioDici}

              delInventarioNovedades={delInventarioNovedades}
              colorSucursal={colorSucursal}
              qInventarioNovedades={qInventarioNovedades}
              setqInventarioNovedades={setqInventarioNovedades}
              qFechaInventarioNovedades={qFechaInventarioNovedades}
              setqFechaInventarioNovedades={setqFechaInventarioNovedades}
              qFechaHastaInventarioNovedades={qFechaHastaInventarioNovedades}
              setqFechaHastaInventarioNovedades={setqFechaHastaInventarioNovedades}
              qSucursalInventarioNovedades={qSucursalInventarioNovedades}
              setqSucursalInventarioNovedades={setqSucursalInventarioNovedades}
              inventarioNovedadesData={inventarioNovedadesData}
              setinventarioNovedadesData={setinventarioNovedadesData}
              getInventarioNovedades={getInventarioNovedades}
              resolveInventarioNovedades={resolveInventarioNovedades}
              sucursales={sucursales}

              qvinculacion1General={qvinculacion1General}
              qvinculacion2General={qvinculacion2General}
              qvinculacion3General={qvinculacion3General}
              qvinculacion4General={qvinculacion4General}
              qvinculacionmarcaGeneral={qvinculacionmarcaGeneral}


              inventarioGeneralqsucursal={inventarioGeneralqsucursal}
              setinventarioGeneralqsucursal={setinventarioGeneralqsucursal}
              setinvsuc_q={setinvsuc_q}
              invsuc_q={invsuc_q}
              invsuc_num={invsuc_num}
              setinvsuc_num={setinvsuc_num}
              invsuc_orderBy={invsuc_orderBy}
              setinvsuc_orderBy={setinvsuc_orderBy}
              setinvsuc_orderColumn={setinvsuc_orderColumn}
              inventariogeneralData={inventariogeneralData}
              getInventarioGeneral={getInventarioGeneral}

              inventariogeneralSelectProEsta={inventariogeneralSelectProEsta}
              setinventariogeneralSelectProEsta={setinventariogeneralSelectProEsta}
              inventariogeneralProEsta={inventariogeneralProEsta}
              setinventariogeneralProEsta={setinventariogeneralProEsta}
              getEstadiscaSelectProducto={getEstadiscaSelectProducto}
            />
          </>
          }

          {permiso([1,2]) && viewmainPanel === "comprasrevision" &&
            <>
              <Comprasmenufactsdigital 
                viewmainPanel={viewmainPanel}
                setviewmainPanel={setviewmainPanel}
                permiso={permiso}
              />
              <CuentasporpagarDetalles
                setdataselectFacts={setdataselectFacts}
                dateFormat={dateFormat}
                colorSucursal={colorSucursal}
                returnCondicion={returnCondicion}
                changeSucursal={changeSucursal}
                abonarFactLote={abonarFactLote}
                setsubviewAgregarFactPago={setsubviewAgregarFactPago}
                abonarFact={abonarFact}
                descuentoGeneralFats={descuentoGeneralFats}
                setdescuentoGeneralFats={setdescuentoGeneralFats}
                sendDescuentoGeneralFats={sendDescuentoGeneralFats}
                dataselectFacts={dataselectFacts}
                selectFacts={selectFacts}
                sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
                setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}

                getSucursales={getSucursales}
                sucursales={sucursales}
                delCuentaPorPagar={delCuentaPorPagar}
                changeAprobarFact={changeAprobarFact}
                cuentaporpagarAprobado={cuentaporpagarAprobado}
                setcuentaporpagarAprobado={setcuentaporpagarAprobado}
                showImageFact={showImageFact}
                cuentasporpagarDetallesView={cuentasporpagarDetallesView}
                setcuentasporpagarDetallesView={setcuentasporpagarDetallesView}
                setviewmainPanel={setviewmainPanel}
                selectCuentaPorPagarId={selectCuentaPorPagarId}
                setSelectCuentaPorPagarId={setSelectCuentaPorPagarId}
                setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
                qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
                selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
                proveedoresList={proveedoresList}
                factSelectIndex={factSelectIndex}
                moneda={moneda}

                setqcuentasPorPagarTipoFact={setqcuentasPorPagarTipoFact}
                qcuentasPorPagarTipoFact={qcuentasPorPagarTipoFact}
                qCampocuentasPorPagarDetalles={qCampocuentasPorPagarDetalles}
                setqCampocuentasPorPagarDetalles={setqCampocuentasPorPagarDetalles}
                numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
                setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
                qFechaCampocuentasPorPagarDetalles={qFechaCampocuentasPorPagarDetalles}
                setqFechaCampocuentasPorPagarDetalles={setqFechaCampocuentasPorPagarDetalles}
                setfechacuentasPorPagarDetalles={setfechacuentasPorPagarDetalles}
                fechacuentasPorPagarDetalles={fechacuentasPorPagarDetalles}
                categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
                setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
                tipocuentasPorPagarDetalles={tipocuentasPorPagarDetalles}
                settipocuentasPorPagarDetalles={settipocuentasPorPagarDetalles}
                OrdercuentasPorPagarDetalles={OrdercuentasPorPagarDetalles}
                setOrdercuentasPorPagarDetalles={setOrdercuentasPorPagarDetalles}

                OrderFechacuentasPorPagarDetalles={OrderFechacuentasPorPagarDetalles}
                setOrderFechacuentasPorPagarDetalles={setOrderFechacuentasPorPagarDetalles}
                setSelectCuentaPorPagarDetalle={setSelectCuentaPorPagarDetalle}
                SelectCuentaPorPagarDetalle={SelectCuentaPorPagarDetalle}
                modeEditarFact={modeEditarFact}
                setselectFactEdit={setselectFactEdit}
                selectProveedorCxp={selectProveedorCxp}
                setselectProveedorCxp={setselectProveedorCxp}
                setcuentasPagosDescripcion={setcuentasPagosDescripcion}
                setcuentasPagosMonto={setcuentasPagosMonto}
                setselectFactPagoid={setselectFactPagoid}
                setselectFactPagoid_sucursal={setselectFactPagoid_sucursal}
                setcuentasPagosMetodo={setcuentasPagosMetodo}
                setcuentasPagosFecha={setcuentasPagosFecha}
                setselectAbonoFact={setselectAbonoFact}
                subViewCuentasxPagar={subViewCuentasxPagar}
                setsubViewCuentasxPagar={setsubViewCuentasxPagar}
              />
              
            </>
          }
          {permiso([1,2,10]) && viewmainPanel === "cargarfactsitems" &&
            <>
              <Comprasmenufactsdigital 
                viewmainPanel={viewmainPanel}
                setviewmainPanel={setviewmainPanel}
                permiso={permiso}
              />
              {subViewInventario == "gestion" ?
                <ComprascargarFactsItems
                  autovincularPedido={autovincularPedido}
                  indexotrasopcionesalterno={indexotrasopcionesalterno}
                  setindexotrasopcionesalterno={setindexotrasopcionesalterno}
                  setotrasopcionesalterno={setotrasopcionesalterno}
                  getotrasopcionesalterno={getotrasopcionesalterno}
                  dataotrasopcionesalterno={dataotrasopcionesalterno}
                  verificarproductomaestro={verificarproductomaestro}
                  buscarInventarioModal={buscarInventarioModal}
                  productosInventarioModal={productosInventarioModal}
                  qBuscarInventarioModal={qBuscarInventarioModal}
                  id_sucursal_select_internoModal={id_sucursal_select_internoModal}
                  setid_sucursal_select_internoModal={setid_sucursal_select_internoModal}
                  setproductosInventarioModal={setproductosInventarioModal}
                  setqBuscarInventarioModal={setqBuscarInventarioModal}
                  InvorderColumnModal={InvorderColumnModal}
                  setInvorderColumnModal={setInvorderColumnModal}
                  InvorderByModal={InvorderByModal}
                  setInvorderByModal={setInvorderByModal}
                  InvnumModal={InvnumModal}
                  setInvnumModal={setInvnumModal}

                  modalmovilx={modalmovilx}
                  setmodalmovilx={setmodalmovilx}
                  modalmovily={modalmovily}
                  setmodalmovily={setmodalmovily}
                  modalmovilshow={modalmovilshow}
                  setmodalmovilshow={setmodalmovilshow}
                  inputbuscarcentralforvincular={inputbuscarcentralforvincular}
                  modalmovilRef={modalmovilRef}
                  idselectproductoinsucursalforvicular={idselectproductoinsucursalforvicular}
                  setidselectproductoinsucursalforvicular={setidselectproductoinsucursalforvicular}
                  openVincularSucursalwithCentral={openVincularSucursalwithCentral}
                  linkproductocentralsucursal={linkproductocentralsucursal}

                  setqcampoBusquedacuentasPorPagarDetalles={setqcampoBusquedacuentasPorPagarDetalles}
                  qcampoBusquedacuentasPorPagarDetalles={qcampoBusquedacuentasPorPagarDetalles}
                  setqinvertircuentasPorPagarDetalles={setqinvertircuentasPorPagarDetalles}
                  qinvertircuentasPorPagarDetalles={qinvertircuentasPorPagarDetalles}
                  getBarrasCargaItems={getBarrasCargaItems}
                  setProductosInventario={setProductosInventario}
                  procesarTextitemscompras={procesarTextitemscompras}
                  subviewcargaritemsfact={subviewcargaritemsfact}
                  setsubviewcargaritemsfact={setsubviewcargaritemsfact}
                  showtextarea={showtextarea}
                  setshowtextarea={setshowtextarea}
                  inputimportitems={inputimportitems}
                  setinputimportitems={setinputimportitems}
                  showFilescxp={showFilescxp}
                  modItemFact={modItemFact}
                  delItemFact={delItemFact}
                  facturaSelectAddItems={facturaSelectAddItems}
                  setfacturaSelectAddItems={setfacturaSelectAddItems}
                  number={number}
                  saveFacturaLote={saveFacturaLote}
                  handleFacturaxLotes={handleFacturaxLotes}
                  selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
                  cuentaporpagarAprobado={cuentaporpagarAprobado}
                  setcuentaporpagarAprobado={setcuentaporpagarAprobado}
                  setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
                  qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
                  setselectProveedorCxp={setselectProveedorCxp}
                  selectProveedorCxp={selectProveedorCxp}
                  proveedoresList={proveedoresList}
                  sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
                  setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}
                  sucursales={sucursales}
                  categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
                  setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
                  qCampocuentasPorPagarDetalles={qCampocuentasPorPagarDetalles}
                  setOrdercuentasPorPagarDetalles={setOrdercuentasPorPagarDetalles}
                  setqCampocuentasPorPagarDetalles={setqCampocuentasPorPagarDetalles}
                  numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
                  setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
                  selectCuentaPorPagarId={selectCuentaPorPagarId}
                  qcuentasPorPagarTipoFact={qcuentasPorPagarTipoFact}
                  dateFormat={dateFormat}
                  returnCondicion={returnCondicion}
                  colorSucursal={colorSucursal}
                  moneda={moneda}

                  qvinculacion1General={qvinculacion1General}
                  setqvinculacion1General={setqvinculacion1General}
                  qvinculacion2General={qvinculacion2General}
                  setqvinculacion2General={setqvinculacion2General}
                  qvinculacion3General={qvinculacion3General}
                  setqvinculacion3General={setqvinculacion3General}
                  qvinculacion4General={qvinculacion4General}
                  setqvinculacion4General={setqvinculacion4General}
                  qvinculacionmarcaGeneral={qvinculacionmarcaGeneral}
                  setqvinculacionmarcaGeneral={setqvinculacionmarcaGeneral}

                  selectIdVinculacion={selectIdVinculacion} 
                  setselectIdVinculacion={setselectIdVinculacion}
                  qvinculacion1={qvinculacion1} 
                  setqvinculacion1={setqvinculacion1}
                  qvinculacion2={qvinculacion2} 
                  setqvinculacion2={setqvinculacion2}
                  qvinculacion3={qvinculacion3} 
                  setqvinculacion3={setqvinculacion3}
                  qvinculacion4={qvinculacion4} 
                  setqvinculacion4={setqvinculacion4}
                  qvinculacionmarca={qvinculacionmarca} 
                  setqvinculacionmarca={setqvinculacionmarca}
                  datavinculacion1={datavinculacion1} 
                  setdatavinculacion1={setdatavinculacion1}
                  datavinculacion2={datavinculacion2} 
                  setdatavinculacion2={setdatavinculacion2}
                  datavinculacion3={datavinculacion3} 
                  setdatavinculacion3={setdatavinculacion3}
                  datavinculacion4={datavinculacion4} 
                  setdatavinculacion4={setdatavinculacion4}
                  datavinculacionmarca={datavinculacionmarca} 
                  setdatavinculacionmarca={setdatavinculacionmarca}
                  inputselectvinculacion1={inputselectvinculacion1} 
                  setinputselectvinculacion1={setinputselectvinculacion1}
                  inputselectvinculacion2={inputselectvinculacion2} 
                  setinputselectvinculacion2={setinputselectvinculacion2}
                  inputselectvinculacion3={inputselectvinculacion3} 
                  setinputselectvinculacion3={setinputselectvinculacion3}
                  inputselectvinculacion4={inputselectvinculacion4} 
                  setinputselectvinculacion4={setinputselectvinculacion4}
                  inputselectvinculacionmarca={inputselectvinculacionmarca} 
                  setinputselectvinculacionmarca={setinputselectvinculacionmarca}
                  inputselectvinculacion1General={inputselectvinculacion1General} 
                  setinputselectvinculacion1General={setinputselectvinculacion1General}
                  inputselectvinculacion2General={inputselectvinculacion2General} 
                  setinputselectvinculacion2General={setinputselectvinculacion2General}
                  inputselectvinculacion3General={inputselectvinculacion3General} 
                  setinputselectvinculacion3General={setinputselectvinculacion3General}
                  inputselectvinculacion4General={inputselectvinculacion4General} 
                  setinputselectvinculacion4General={setinputselectvinculacion4General}
                  inputselectvinculacionmarcaGeneral={inputselectvinculacionmarcaGeneral} 
                  setinputselectvinculacionmarcaGeneral={setinputselectvinculacionmarcaGeneral}
                  getDatinputSelectVinculacion={getDatinputSelectVinculacion}
                  saveCuatroNombres={saveCuatroNombres}

                  newNombre1={newNombre1}
                  setnewNombre1={setnewNombre1}
                  newNombre2={newNombre2}
                  setnewNombre2={setnewNombre2}
                  newNombre3={newNombre3}
                  setnewNombre3={setnewNombre3}
                  newNombre4={newNombre4}
                  setnewNombre4={setnewNombre4}
                  newNombremarca={newNombremarca}
                  setnewNombremarca={setnewNombremarca}

                  setporcenganancia={setporcenganancia}
                  productosInventario={productosInventario}
                  qBuscarInventario={qBuscarInventario}
                  buscarInventario={buscarInventario}
                  setQBuscarInventario={setQBuscarInventario}
                  type={type}
                  changeInventario={changeInventario}
                  Invnum={Invnum}
                  setInvnum={setInvnum}
                  InvorderColumn={InvorderColumn}
                  setInvorderColumn={setInvorderColumn}
                  InvorderBy={InvorderBy}
                  setInvorderBy={setInvorderBy}
                  inputBuscarInventario={inputBuscarInventario}
                  guardarNuevoProductoLote={guardarNuevoProductoLote}
                  refsInpInvList={refsInpInvList}
                  categorias={categorias}
                  marcas={marcas}
                  catGenerals={catGenerals}

                  getMarcas={getMarcas}
                  getCatGenerals={getCatGenerals}
                  getCategorias={getCategorias}
                  addnewNombre={addnewNombre}
                />
                : null}

              {subViewInventario == "departamentos" ?
                <DepartamentosInventario
                  getCategorias={getCategorias}
                  addNewCategorias={addNewCategorias}
                  categoriasDescripcion={categoriasDescripcion}
                  setcategoriasDescripcion={setcategoriasDescripcion}
                  indexSelectCategorias={indexSelectCategorias}
                  setIndexSelectCategorias={setIndexSelectCategorias}
                  qBuscarCategorias={qBuscarCategorias}
                  setQBuscarCategorias={setQBuscarCategorias}
                  delCategorias={delCategorias}
                  categorias={categorias}
                />
                : null}

              {subViewInventario == "catgeneral" ?
                <CatGeneral
                  getCatGenerals={getCatGenerals}
                  addNewCatGenerals={addNewCatGenerals}
                  catGeneralsDescripcion={catGeneralsDescripcion}
                  setcatGeneralsDescripcion={setcatGeneralsDescripcion}
                  indexSelectCatGenerals={indexSelectCatGenerals}
                  setIndexSelectCatGenerals={setIndexSelectCatGenerals}
                  qBuscarCatGenerals={qBuscarCatGenerals}
                  setQBuscarCatGenerals={setQBuscarCatGenerals}
                  delCatGenerals={delCatGenerals}
                  catGenerals={catGenerals}
                />
                : null}

              {subViewInventario == "marcas" ?
                <Marcas
                  getMarcas={getMarcas}
                  addNewMarcas={addNewMarcas}
                  marcasDescripcion={marcasDescripcion}
                  setmarcasDescripcion={setmarcasDescripcion}
                  indexSelectMarcas={indexSelectMarcas}
                  setIndexSelectMarcas={setIndexSelectMarcas}
                  qBuscarMarcas={qBuscarMarcas}
                  setQBuscarMarcas={setQBuscarMarcas}
                  delMarcas={delMarcas}
                  marcas={marcas}
                />
                : null}
            </>
          }

          {permiso([1,2,5,13]) && viewmainPanel === "gastos" && 
            <Gastos
              gastosct={gastosct}
              setgastosct={setgastosct}
              gastosunidad={gastosunidad}
              setgastosunidad={setgastosunidad}
              dataAprobacionFlujoCaja={dataAprobacionFlujoCaja}
              qfechadesdeAprobaFlujCaja={qfechadesdeAprobaFlujCaja}
              setqfechadesdeAprobaFlujCaja={setqfechadesdeAprobaFlujCaja}
              qfechahastaAprobaFlujCaja={qfechahastaAprobaFlujCaja}
              setqfechahastaAprobaFlujCaja={setqfechahastaAprobaFlujCaja}
              qAprobaFlujCaja={qAprobaFlujCaja}
              setqAprobaFlujCaja={setqAprobaFlujCaja}
              qCategoriaAprobaFlujCaja={qCategoriaAprobaFlujCaja}
              setqCategoriaAprobaFlujCaja={setqCategoriaAprobaFlujCaja}
              qSucursalAprobaFlujCaja={qSucursalAprobaFlujCaja}
              setqSucursalAprobaFlujCaja={setqSucursalAprobaFlujCaja}
              getAprobacionFlujoCaja={getAprobacionFlujoCaja}

              fechasMain1={fechasMain1}
              fechasMain2={fechasMain2}
              setfechasMain1={setfechasMain1}
              setfechasMain2={setfechasMain2}
              getsucursalDetallesData={getsucursalDetallesData}
              sucursalSelect={sucursalSelect}
              setsucursalSelect={setsucursalSelect}
              setsucursalDetallesData={setsucursalDetallesData}
              sucursalDetallesData={sucursalDetallesData}
              qestatusaprobaciocaja={qestatusaprobaciocaja}
              setqestatusaprobaciocaja={setqestatusaprobaciocaja}
              aprobarMovCajaFuerte={aprobarMovCajaFuerte}

              gastosBancoDivisaDestino={gastosBancoDivisaDestino}
              setgastosBancoDivisaDestino={setgastosBancoDivisaDestino}
              setConciliarMovCajaMatriz={setConciliarMovCajaMatriz}
              selectdepositobanco={selectdepositobanco}
              bancodepositobanco={bancodepositobanco}
              setbancodepositobanco={setbancodepositobanco}
              fechadepositobanco={fechadepositobanco}
              setfechadepositobanco={setfechadepositobanco}
              depositarmatrizalbanco={depositarmatrizalbanco}
              setselectdepositobanco={setselectdepositobanco}
              colors={colors}
              setcontrolbancoQ={setcontrolbancoQ}
              controlbancoQ={controlbancoQ}
              setcontrolbancoQCategoria={setcontrolbancoQCategoria}
              controlbancoQCategoria={controlbancoQCategoria}
              setcontrolbancoQDesde={setcontrolbancoQDesde}
              controlbancoQDesde={controlbancoQDesde}
              setcontrolbancoQHasta={setcontrolbancoQHasta}
              controlbancoQHasta={controlbancoQHasta}
              controlbancoQBanco={controlbancoQBanco}
              setcontrolbancoQBanco={setcontrolbancoQBanco}
              controlbancoQSiliquidado={controlbancoQSiliquidado}
              setcontrolbancoQSiliquidado={setcontrolbancoQSiliquidado}
              controlbancoQSucursal={controlbancoQSucursal}
              setcontrolbancoQSucursal={setcontrolbancoQSucursal}
              movBancosData={movBancosData}
              getMovBancos={getMovBancos}
              number={number}
              sendMovimientoBanco={sendMovimientoBanco}
              cuentasPagosDescripcion={cuentasPagosDescripcion}
              setcuentasPagosDescripcion={setcuentasPagosDescripcion}
              cuentasPagosMonto={cuentasPagosMonto}
              setcuentasPagosMonto={setcuentasPagosMonto}
              cuentasPagosFecha={cuentasPagosFecha}
              setcuentasPagosFecha={setcuentasPagosFecha}
              cuentasPagosMetodo={cuentasPagosMetodo}
              setcuentasPagosMetodo={setcuentasPagosMetodo}
              cuentasPagosMetodoDestino={cuentasPagosMetodoDestino}
              setcuentasPagosMetodoDestino={setcuentasPagosMetodoDestino}
              setiscomisiongasto={setiscomisiongasto}
              iscomisiongasto={iscomisiongasto}
              comisionpagomovilinterban={comisionpagomovilinterban}
              setcomisionpagomovilinterban={setcomisionpagomovilinterban}

              indexsubviewproveedordetalles={indexsubviewproveedordetalles}
              setindexsubviewproveedordetalles={setindexsubviewproveedordetalles}
              qbuscarcat={qbuscarcat}
              setqbuscarcat={setqbuscarcat}
              indexviewcatdetalles={indexviewcatdetalles}
              setindexviewcatdetalles={setindexviewcatdetalles}
              indexsubviewcatdetalles={indexsubviewcatdetalles}
              setindexsubviewcatdetalles={setindexsubviewcatdetalles}

              indexviewsucursaldetalles={indexviewsucursaldetalles}
              setindexviewsucursaldetalles={setindexviewsucursaldetalles}
              indexsubviewsucursaldetalles={indexsubviewsucursaldetalles}
              setindexsubviewsucursaldetalles={setindexsubviewsucursaldetalles}
              removeMoneda={removeMoneda}
              getGastosDistribucion={getGastosDistribucion}
              distribucionGastosCat={distribucionGastosCat}
              categoriasCajas={categoriasCajas}
              colorsGastosCat={colorsGastosCat}
              colorSucursal={colorSucursal}
              setlistBeneficiario={setlistBeneficiario}
              addBeneficiarioList={addBeneficiarioList}
              listBeneficiario={listBeneficiario}
              modeMoneda={modeMoneda}
              setmodeMoneda={setmodeMoneda}
              modeEjecutor={modeEjecutor}
              setmodeEjecutor={setmodeEjecutor}
              formatAmount={formatAmount}
              nominaData={nominaData}

              gastosData={gastosData}
              setgastosData={setgastosData}

              gastoscatgeneral={gastoscatgeneral}
              setgastoscatgeneral={setgastoscatgeneral}
              gastosingreso_egreso={gastosingreso_egreso}
              setgastosingreso_egreso={setgastosingreso_egreso}
              gastostypecaja={gastostypecaja}
              setgastostypecaja={setgastostypecaja}
              gastosorder={gastosorder}
              setgastosorder={setgastosorder}
              gastosfieldorder={gastosfieldorder}
              setgastosfieldorder={setgastosfieldorder}

              gastosQ={gastosQ}
              setgastosQ={setgastosQ}
              gastosQCategoria={gastosQCategoria}
              setgastosQCategoria={setgastosQCategoria}
              gastosQsucursal={gastosQsucursal}
              setgastosQsucursal={setgastosQsucursal}
              gastosQFecha={gastosQFecha}
              setgastosQFecha={setgastosQFecha}
              gastosQFechaHasta={gastosQFechaHasta}
              setgastosQFechaHasta={setgastosQFechaHasta}
              gastosDescripcion={gastosDescripcion}
              setgastosDescripcion={setgastosDescripcion}
              gastosMonto={gastosMonto}
              setgastosMonto={setgastosMonto}
              gastosCategoria={gastosCategoria}
              setgastosCategoria={setgastosCategoria}
              gastosBeneficiario={gastosBeneficiario}
              setgastosBeneficiario={setgastosBeneficiario}
              gastosFecha={gastosFecha}
              setgastosFecha={setgastosFecha}
              setgastosMonto_dolar={setgastosMonto_dolar}              
              gastosMonto_dolar={gastosMonto_dolar}
              setgastosTasa={setgastosTasa}              
              gastosTasa={gastosTasa}
              gastosBanco={gastosBanco}
              setgastosBanco={setgastosBanco}
              opcionesMetodosPago={opcionesMetodosPago}
              subviewGastos={subviewGastos}
              setsubviewGastos={setsubviewGastos}
              selectIdGastos={selectIdGastos}
              setselectIdGastos={setselectIdGastos}
              delGasto={delGasto}
              saveNewGasto={saveNewGasto}
              getGastos={getGastos}
              setNewGastosInput={setNewGastosInput}
              setEditGastosInput={setEditGastosInput}
              qBeneficiario={qBeneficiario}
              setqBeneficiario={setqBeneficiario}
              qSucursal={qSucursal}
              setqSucursal={setqSucursal}
              qCatGastos={qCatGastos}
              setqCatGastos={setqCatGastos}
              getSucursales={getSucursales}
              sucursales={sucursales}
              getPersonal={getPersonal}
              qNomina={qNomina}
              setqNomina={setqNomina}
              moneda={moneda}



              setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
              getAlquileres={getAlquileres}
              controlefecQ={controlefecQ}
              setcontrolefecQ={setcontrolefecQ}
              controlefecQDesde={controlefecQDesde}
              setcontrolefecQDesde={setcontrolefecQDesde}
              controlefecQHasta={controlefecQHasta}
              setcontrolefecQHasta={setcontrolefecQHasta}
              controlefecData={controlefecData}
              controlefecSelectGeneral={controlefecSelectGeneral}
              controlefecNewConcepto={controlefecNewConcepto}
              setcontrolefecNewConcepto={setcontrolefecNewConcepto}
              controlefecNewFecha={controlefecNewFecha}
              setcontrolefecNewFecha={setcontrolefecNewFecha}
              controlefecNewCategoria={controlefecNewCategoria}
              setcontrolefecNewCategoria={setcontrolefecNewCategoria}
              controlefecNewMonto={controlefecNewMonto}
              setcontrolefecNewMonto={setcontrolefecNewMonto}
              getControlEfec={getControlEfec}
              setControlEfec={setControlEfec}
              setcontrolefecQCategoria={setcontrolefecQCategoria}
              controlefecQCategoria={controlefecQCategoria}
              controlefecNewMontoMoneda={controlefecNewMontoMoneda}
              setcontrolefecNewMontoMoneda={setcontrolefecNewMontoMoneda}
              getcatsCajas={getCatCajas}
              delCaja={delCaja}
              personalNomina={nominaData}
              getNomina={getPersonalNomina}
              setopenModalNuevoEfectivo={setopenModalNuevoEfectivo}
              openModalNuevoEfectivo={openModalNuevoEfectivo}
              verificarMovPenControlEfec={verificarMovPenControlEfec}
              verificarMovPenControlEfecTRANFTRABAJADOR={verificarMovPenControlEfecTRANFTRABAJADOR}
              allProveedoresCentral={proveedoresList}
              getAllProveedores={getProveedores}
              alquileresData={alquileresData}
              sucursalesCentral={sucursales}
              transferirpedidoa={transferirpedidoa}
              settransferirpedidoa={settransferirpedidoa}
              reversarMovPendientes={reversarMovPendientes}
              aprobarRecepcionCaja={aprobarRecepcionCaja}
              dolar={dolar}
              peso={peso}

            />
          }

          {permiso([1,2]) && viewmainPanel === "pedir" &&
            <Pedir
              productos={productos}
              getProductos={getProductos}
              moneda={moneda}
              qProductosMain={qProductosMain}
              setQProductosMain={setQProductosMain}
              openSelectProvNewPedCompras={openSelectProvNewPedCompras}
              setopenSelectProvNewPedComprasCheck={setopenSelectProvNewPedComprasCheck}
              openSelectProvNewPedComprasCheck={openSelectProvNewPedComprasCheck}
              NewPedComprasSelectProd={NewPedComprasSelectProd}
              setNewPedComprasSelectProd={setNewPedComprasSelectProd}
              subViewCompras={subViewCompras}
              setsubViewCompras={setsubViewCompras}
              precioxproveedor={precioxproveedor}
              selectPrecioxProveedorProducto={selectPrecioxProveedorProducto}
              selectPrecioxProveedorProveedor={selectPrecioxProveedorProveedor}
              setselectPrecioxProveedorProducto={setselectPrecioxProveedorProducto}
              setselectPrecioxProveedorProveedor={setselectPrecioxProveedorProveedor}
              selectPrecioxProveedorSave={selectPrecioxProveedorSave}
              qBuscarProveedor={qBuscarProveedor}
              setQBuscarProveedor={setQBuscarProveedor}
              proveedoresList={proveedoresList}
              getProveedores={getProveedores}
              selectPrecioxProveedorPrecio={selectPrecioxProveedorPrecio}
              setselectPrecioxProveedorPrecio={setselectPrecioxProveedorPrecio}
              getPrecioxProveedor={getPrecioxProveedor}
            >

            </Pedir>
          }

          {permiso([1,2]) && viewmainPanel === "proveedores" && 
            <Proveedores
              getProveedores={getProveedores}
              setviewmainPanel={setviewmainPanel}
              number={number}
              setProveedor={setProveedor}
              proveedordescripcion={proveedordescripcion}
              setproveedordescripcion={setproveedordescripcion}
              proveedorrif={proveedorrif}
              setproveedorrif={setproveedorrif}
              proveedordireccion={proveedordireccion}
              setproveedordireccion={setproveedordireccion}
              proveedortelefono={proveedortelefono}
              setproveedortelefono={setproveedortelefono}
              setIndexSelectProveedores={setIndexSelectProveedores}
              indexSelectProveedores={indexSelectProveedores}
              qBuscarProveedor={qBuscarProveedor}
              setQBuscarProveedor={setQBuscarProveedor}
              proveedoresList={proveedoresList}
              delProveedor={delProveedor}
            />
          }

          


          


          {permiso([1,2,3,5,7,8,10]) && viewmainPanel === "sucursales" &&
            <PanelSucursales
              controlefecQDescripcion={controlefecQDescripcion}
              setcontrolefecQDescripcion={setcontrolefecQDescripcion}
              controlefecSelectCat={controlefecSelectCat}
              setcontrolefecSelectCat={setcontrolefecSelectCat}
              permiso={permiso}
              user={user}
              changeLiquidacionPagoElec={changeLiquidacionPagoElec}
              getPersonalCargos={getPersonalCargos}
              cargosData={cargosData}
              sucursales={sucursales}
              sucursalSelect={sucursalSelect}
              setsucursalSelect={setsucursalSelect}
              subviewpanelsucursales={subviewpanelsucursales}
              setsubviewpanelsucursales={setsubviewpanelsucursales}
              fechasMain1={fechasMain1}
              fechasMain2={fechasMain2}

              getSucursales={getSucursales}
              getsucursalListData={getsucursalListData}
              getsucursalDetallesData={getsucursalDetallesData}

              filtronominaq={filtronominaq}
              setfiltronominaq={setfiltronominaq}
              filtronominacargo={filtronominacargo}
              setfiltronominacargo={setfiltronominacargo}

              sucursalDetallesData={sucursalDetallesData}

              invsuc_itemCero={invsuc_itemCero}
              setinvsuc_itemCero={setinvsuc_itemCero}
              invsuc_q={invsuc_q}
              setinvsuc_q={setinvsuc_q}
              invsuc_exacto={invsuc_exacto}
              setinvsuc_exacto={setinvsuc_exacto}
              invsuc_num={invsuc_num}
              setinvsuc_num={setinvsuc_num}
              invsuc_orderColumn={invsuc_orderColumn}
              setinvsuc_orderColumn={setinvsuc_orderColumn}
              invsuc_orderBy={invsuc_orderBy}
              setinvsuc_orderBy={setinvsuc_orderBy}

              controlefecSelectGeneral={controlefecSelectGeneral}
              setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
              moneda={moneda}

              fechaSelectAuditoria={fechaSelectAuditoria}
              setfechaSelectAuditoria={setfechaSelectAuditoria}
              bancoSelectAuditoria={bancoSelectAuditoria}
              setbancoSelectAuditoria={setbancoSelectAuditoria}
              SaldoInicialSelectAuditoria={SaldoInicialSelectAuditoria}
              setSaldoInicialSelectAuditoria={setSaldoInicialSelectAuditoria}
              SaldoActualSelectAuditoria={SaldoActualSelectAuditoria}
              setSaldoActualSelectAuditoria={setSaldoActualSelectAuditoria}
              colorsGastosCat={colorsGastosCat}
              getCatCajas={getCatCajas}

            >
              <FechasMain
                fechasMain1={fechasMain1}
                fechasMain2={fechasMain2}
                setfechasMain1={setfechasMain1}
                setfechasMain2={setfechasMain2}
              />

            </PanelSucursales>
          }

          {permiso([1,2]) && viewmainPanel === "comovamos" &&
            <ComoVamos
              sendReporteDiario={sendReporteDiario}
              fechareportediario={fechareportediario}
              setfechareportediario={setfechareportediario}

              colorSucursal={colorSucursal}
              sendCuadreGeneral={sendCuadreGeneral}
              cuantotengobanco={cuantotengobanco}
              setcuantotengobanco={setcuantotengobanco}
              cuantotengoefectivo={cuantotengoefectivo}
              setcuantotengoefectivo={setcuantotengoefectivo}

              balanceGeneralData={balanceGeneralData}
              getBalanceGeneral={getBalanceGeneral}
              sucursalBalanceGeneral={sucursalBalanceGeneral}
              setsucursalBalanceGeneral={setsucursalBalanceGeneral}
              setfechaBalanceGeneral={setfechaBalanceGeneral}
              fechaBalanceGeneral={fechaBalanceGeneral}
              setfechaHastaBalanceGeneral={setfechaHastaBalanceGeneral}
              fechaHastaBalanceGeneral={fechaHastaBalanceGeneral}
              getsucursalDetallesData={getsucursalDetallesData}
              sucursalDetallesData={sucursalDetallesData}
              subviewpanelsucursales={subviewpanelsucursales}
              setsubviewpanelsucursales={setsubviewpanelsucursales}
              moneda={moneda}
              sucursales={sucursales}
              colorsGastosCat={colorsGastosCat}

            />
          }


        </Panel>
      </>}
    </>
  );
}
render(<Home />, document.getElementById('app'));

