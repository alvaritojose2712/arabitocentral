/* import { useHotkeys } from 'react-hotkeys-hook'; */

import { cloneDeep } from "lodash";

import { useState, useEffect, useRef } from 'react';
import { render } from 'react-dom';
import db from '../database/database';
import Header from './header';
import SelectSucursal from './selectSucursal';

import VentasComponent from './ventas';
import GastosComponent from './gastos';

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

import GestionInventario from './panel/gestioninventario'
import DepartamentosInventario from './panel/departamentosInventario'
import CatGeneral from './panel/catGeneral'
import Marcas from './panel/marcas'

import NavInventario from './panel/navInventario'

import Usuarios from './usuarios';
import Compras from './compras';

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
  const [showCantidadCarrito, setshowCantidadCarrito] = useState("buscar")

  const [ctSucursales, setctSucursales] = useState([])

  const [id_pedido, setid_pedido] = useState("nuevo")
  const [pedidoList, setpedidoList] = useState([])

  const [qpedido, setqpedido] = useState("")
  const [qpedidoDateFrom, setqpedidoDateFrom] = useState("")
  const [qpedidoDateTo, setqpedidoDateTo] = useState("")
  const [qpedidoOrderBy, setqpedidoOrderBy] = useState("id")
  const [qpedidoOrderByDescAsc, setqpedidoOrderByDescAsc] = useState("desc")
  const [pedidos, setpedidos] = useState([])
  const [pedidoData, setpedidoData] = useState(null)
  const [qestadopedido, setqestadopedido] = useState(0)

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
  const [indexSelectInventario, setIndexSelectInventario] = useState(null)

  const [invsuc_itemCero, setinvsuc_itemCero] = useState("")
  const [invsuc_q, setinvsuc_q] = useState("")
  const [invsuc_exacto, setinvsuc_exacto] = useState("")
  const [invsuc_num, setinvsuc_num] = useState("25")
  const [invsuc_orderColumn, setinvsuc_orderColumn] = useState("descripcion")
  const [invsuc_orderBy, setinvsuc_orderBy] = useState("desc")
  const [controlefecSelectGeneral, setcontrolefecSelectGeneral] = useState(1)



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



  ///Proveedores Props

  const [qBuscarProveedor, setQBuscarProveedor] = useState("")
  const [proveedoresList, setProveedoresList] = useState([])




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
    "0102": ["#d70808", "#fff"],
    "0108": ["#0c3868", "#fff"],
    "0105": ["#0091ff", "#000000"],
    "0134": ["rgb(10, 132, 120)", "#fff"],
    "0175": ["#ff90b3", "#000000"],
    "0191": ["#ffd102", "#000000"],
    "0151": ["#14ffe7", "#000000"],
    "0114": ["#d8ff14", "#000000"],
    "ZELLE": ["#6d093b", "#fff"],
    "BINANCE": ["#836901", "#000000"],
    "AirTM": ["#6d093b", "#000000"],
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
  const delItemFact = e => {
    let id = e.currentTarget.attributes["data-id"].value

    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delItemFact({ id }).then(res => {
        setLoading(false)
        notificar(res)
        if (res.data.estado) {
          getFacturas(false)
          buscarInventario()
        }
      })
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
  const guardarNuevoProductoLote = () => {
    let id_factura = null

    if (factSelectIndex != null) {
      if (facturas[factSelectIndex]) {
        id_factura = facturas[factSelectIndex].id
      }
    }
    let lotesFil = productosInventario.filter(e => e.type)


    let checkempty = lotesFil.filter(e =>
      e.codigo_barras == "" ||
      e.descripcion == "" ||
      e.id_categoria == "" ||
      e.id_catgeneral == "" ||
      e.id_marca == "" ||
      e.unidad == "" ||
      e.cantidad == "" ||
      e.precio_base == "" ||
      e.precio == "")

    if (lotesFil.length && !checkempty.length) {

      setLoading(true)
      db.guardarNuevoProductoLote({ lotes: lotesFil, id_factura }).then(res => {
        notificar(res)
        setLoading(false)
        try {
          if (res.data.estado) {
            getFacturas(null)

            buscarInventario()

          }
        } catch (err) { }
      })
    } else {
      alert("¡Error con los campos! Algunos pueden estar vacíos" + JSON.stringify(checkempty))
    }

  }
  const changeInventario = (val, i, id, type, name = null) => {
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

  const buscarInventario = e => {

    let checkempty = productosInventario.filter(e => e.type).filter(e =>
      e.codigo_barras == "" ||
      e.descripcion == "" ||
      e.id_categoria == "" ||
      e.unidad == "" ||
      e.cantidad == "" ||
      e.precio_base == "" ||
      e.precio == "")

    if (!checkempty.length) {
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
          orderBy: InvorderBy
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

    } else {
      alert("Hay productos pendientes en carga de Inventario List!")
    }



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
    getPedidos()
  }, [
    qpedido,
    qpedidoDateFrom,
    qpedidoDateTo,
    qpedidoOrderBy,
    qpedidoOrderByDescAsc,
    qestadopedido
  ])


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
    return number(val.replace("Bs.","").replace("$","").replace(" ","").replace(".","").replace(",","."))
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
    const date = new Date(input_D);

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
    format_D = format_D.replace("dd", day.toString().padStart(2,"0"));

    return format_D;
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

  const getPedidos = e => {
    setLoading(true)
    db.getPedidos({ qpedido, qpedidoDateFrom, qpedidoDateTo, qpedidoOrderBy, qpedidoOrderByDescAsc, qestadopedido }).then(res => {
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
  const selectPedido = e => {
    setLoading(true)
    let id;
    if (e) {
      id = e.currentTarget.attributes["data-id"].value
    } else {
      if (pedidoData.id) {
        id = pedidoData.id
      } else {
        alert("No hay pedido seleccionado")
      }
    }

    if (id) {
      db.getPedido({ id }).then(res => {
        setLoading(false)
        setpedidoData(res.data)

        if (res.data) {
          setshowCantidadCarrito("pedidoSelect")
        }

      })
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
  const [qcuentasPorPagar, setqcuentasPorPagar] = useState("")

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
  const [nominaFechadeIngreso, setnominaFechadeIngreso] = useState("")
  const [nominaGradoInstruccion, setnominaGradoInstruccion] = useState("")
  const [nominaCargo, setnominaCargo] = useState("")
  const [nominaSucursal, setnominaSucursal] = useState("")

  const [indexSelectNomina, setIndexSelectNomina] = useState(null)
  const [qNomina, setqNomina] = useState("")
  const [qSucursalNomina, setqSucursalNomina] = useState("")
  const [qCargoNomina, setqCargoNomina] = useState("")

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

  const [qBuscarUsuario, setQBuscarUsuario] = useState("");
  const [indexSelectUsuarios, setIndexSelectUsuarios] = useState(null);

  const [filtronominaq, setfiltronominaq] = useState("")
  const [filtronominacargo, setfiltronominacargo] = useState("")

  const categoriaMovBanco = [
    {id:1, descripcion: "INGRESO DE SUCURSAL"},
    {id:2, descripcion: "PAGO PROVEEDOR"},
    {id:3, descripcion: "INVERSION"},
    {id:4, descripcion: "GASTOS"},
    {id:5, descripcion: "COMPRAS CONTADO"},
    {id:6, descripcion: "TRASPASO ENTRE CUENTA"},
    {id:7, descripcion: "COMISIÓN"},
  ]

  useEffect(() => {
    getToday()
    getSucursales()
    getProveedores()
    getMetodosPago()


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
      
    })
  }
  const getSucursales = (q="",callback=null) => {
    setLoading(true)
    db.getSucursales({
      q
    }).then(res => {
      res.data = res.data.map(e=>{
        e.color =  "#"+colorFun(1575*e.id+(e.codigo).slice(0,6))
        return e
      })
      setsucursales(res.data)
      setLoading(false)
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
    setLoading(true)
    db.getsucursalListData({
      fechasMain1,
      fechasMain2,
      filtros,

      subviewpanelsucursales,
    }).then(res => {
      setsucursalListData(res.data)
      setLoading(false)
    })
  }
  const aprobarMovCajaFuerte = (id,tipo) => {
    if (confirm("Confirma ("+tipo+")")) {
      db.aprobarMovCajaFuerte({tipo,id}).then(res=>{
        notificar(res.data)
        getsucursalDetallesData(null,"aprobacioncajafuerte") 
      })
    }
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
  const [subviewAuditoria, setsubviewAuditoria] = useState("cuadre") 
  const [subviewAuditoriaGeneral, setsubviewAuditoriaGeneral] = useState("") //efectivo banco transferencias 


  const [selectCuentaPorPagarId, setSelectCuentaPorPagarId] = useState(null)
  const [qcuentasPorPagarDetalles, setqcuentasPorPagarDetalles] = useState("")
  
  
  const [qcuentasPorPagarTipoFact, setqcuentasPorPagarTipoFact] = useState("")
  const [qCampocuentasPorPagarDetalles, setqCampocuentasPorPagarDetalles] = useState("updated_at")
  const [qFechaCampocuentasPorPagarDetalles, setqFechaCampocuentasPorPagarDetalles] = useState("")
  const [fechacuentasPorPagarDetalles, setfechacuentasPorPagarDetalles] = useState("")
  const [categoriacuentasPorPagarDetalles, setcategoriacuentasPorPagarDetalles] = useState("")
  const [tipocuentasPorPagarDetalles, settipocuentasPorPagarDetalles] = useState("")
  const [OrdercuentasPorPagarDetalles, setOrdercuentasPorPagarDetalles] = useState("desc")
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

  const [dataselectFacts, setdataselectFacts] = useState({
    "sum": 0,
    "data": []
  })

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
    db.showImageFact(id)
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
    db.changeAprobarFact({id}).then(res=>{
      selectCuentaPorPagarProveedorDetallesFun()
      setSelectCuentaPorPagarDetalle(null)
    })
  }
  
  useEffect(()=>{
    selectCuentaPorPagarProveedorDetallesFun()
  },[
      categoriacuentasPorPagarDetalles,
      tipocuentasPorPagarDetalles,
      qcuentasPorPagarTipoFact,
      
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
      
      qCampocuentasPorPagarDetalles,
      qcuentasPorPagarDetalles,
      OrdercuentasPorPagarDetalles,
      cuentaporpagarAprobado,
      sucursalcuentasPorPagarDetalles,
      type,
      id_facts_force,
    }
    if (type=="buscar") {
      setSelectCuentaPorPagarId([])

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
        qcuentasPorPagar,
      },

      subviewpanelsucursales: subviewpanelsucursalesforce ? subviewpanelsucursalesforce : subviewpanelsucursales,
      sucursalSelect,
    }).then(res => {
      setsucursalDetallesData(res.data)
      setLoading(false)
    })
  }



  

  /// Nomina ///


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

      id: indexSelectNomina
    }).then(({ data }) => {
      if (data.estado) {
        getPersonalNomina()
      }
      notificar(data.msj)
    })
  }
  const getPersonalNomina = event => {
    if (event) {
      event.preventDefault()
    }
    db.getPersonalNomina({
      fechasMain1,
      fechasMain2,
      qNomina,
      qSucursalNomina,
      qCargoNomina,
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
  const getCatGeneralFun = (id_cat) => {

    let catgeneralList = [
        {color:"#cc3300", nombre:"EGRESOS",},
        {color:"#3E7B00", nombre:"INGRESO",},
        {color:"#ff9900", nombre:"GASTO",},
        {color:"#A07800", nombre:"GASTO GENERAL",},
        {color:"#808080", nombre:"MOVIMIENTO EXTERNO",},
        {color:"#595959", nombre:"MOVIMIENTO NULO INTERNO",},
        {color:"#A8A805", nombre:"CAJA GENERAL IDEPENDIENTE",},
    ]
    let catfilter = categoriasCajas.filter(e=>e.indice==id_cat)
    if (catfilter.length) {
      if (catfilter[0].catgeneral) {
        return catgeneralList[catfilter[0].catgeneral]
      }else if(catfilter[0].catgeneral==0){
        return catgeneralList[catfilter[0].catgeneral]
      }else{
        return {color:"", nombre:""}
      }
    }

    return {color:"", nombre:""}

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
      name: "POR PAGAR"
    },
    {
      route: "creditos",
      name: "POR COBRAR"
    },
    {
      route: "auditoria",
      name: "AUDITORÍA"
    },
    {
      route: "gastos",
      name: "GASTOS"
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
      route: "administracion",
      name: "ADMINISTRACIÓN"
    },

  ]

 

  const [opcionesMetodosPago,setopcionesMetodosPago] = useState([])
  const [bancosdata,setbancosdata] = useState([])
  const [fechaSelectAuditoria,setfechaSelectAuditoria] = useState("")
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

  const [selectConciliacionData,setselectConciliacionData] = useState("")

  const selectConciliacion = (banco,fecha) => {
    setselectConciliacionData(banco+"-"+fecha)
    let fil = bancosdata.xfechaCuadre.filter(e=>e.banco==banco && e.fecha==fecha)
    if (fil.length) {
      let g = fil[0].guardado
      if (g) {
        setsaldoactualbancofecha(g.saldo)
      }else{
        setsaldoactualbancofecha("")

      }
    }
  }

  const sendsaldoactualbancofecha = (banco,fecha) => {

    db.sendsaldoactualbancofecha({
      banco,
      fecha,
      saldo: saldoactualbancofecha,
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
      getBancosData("liquidar")
      setinpmontoLiquidar("")

    })
  }
  const reverserLiquidar = id => {
    if (confirm("Confirme Reverso")) {
      db.reverserLiquidar({id}).then(res=>{
        getBancosData()
      })
    }
  }

  const changeBank = (id,type) => {
    let codigos = opcionesMetodosPago.map(e=>e.codigo)
    let banco = window.prompt("Editar "+type)

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

  const getBancosData = (subviewforced=null) => {
    if (fechaSelectAuditoria && fechaHastaSelectAuditoria) {
      db.getBancosData({
        fechaSelectAuditoria,
        fechaHastaSelectAuditoria,
        bancoSelectAuditoria,
        sucursalSelectAuditoria,
        qdescripcionbancosdata,
        subviewAuditoria: subviewforced?subviewforced:subviewAuditoria,
        orderAuditoria,
        orderColumnAuditoria,
      }).then(res=>{
        setmovimientoAuditoria([])

        if (res.data.estado) {
          setbancosdata(res.data)
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
      !cuentasPagosCategoria
    ) {
      alert("Campos Vacíos!")      
    }else{
      db.sendMovimientoBanco({
        cuentasPagosDescripcion,
        cuentasPagosMonto,
        cuentasPagosMetodo,
        cuentasPagosFecha,
        cuentasPagoTipo,
        cuentasPagosCategoria,
        cuentasPagosPuntooTranfe,
        cuentasPagosSucursal,
      }).then(res=>{
        if (res.data.estado) {
          getBancosData()
        }
        notificar(res.data.msj)
      })
    }
  }

  const [modeMoneda, setmodeMoneda] = useState("dolar")
	const [modeEjecutor, setmodeEjecutor] = useState("personal")
  
  const [subViewCuentasxPagar, setsubViewCuentasxPagar] = useState("disponible")

  const [gastosData,setgastosData] = useState([])
  const [gastosQ,setgastosQ] = useState("")
  const [gastosQCategoria,setgastosQCategoria] = useState("")
  const [gastosQFecha,setgastosQFecha] = useState("")
  const [gastosQFechaHasta,setgastosQFechaHasta] = useState("")
  
  const [gastosDescripcion,setgastosDescripcion] = useState("")
  const [gastosMonto,setgastosMonto] = useState("")
  const [gastosCategoria,setgastosCategoria] = useState("")
  const [gastosBeneficiario,setgastosBeneficiario] = useState("")
  const [gastosFecha,setgastosFecha] = useState("")
  const [gastosBanco,setgastosBanco] = useState("")

  const [gastosMonto_dolar, setgastosMonto_dolar] = useState("")
  const [gastosTasa, setgastosTasa] = useState("")

  const [subviewGastos,setsubviewGastos] = useState("cargar")
  const [selectIdGastos,setselectIdGastos] = useState("")
  
  const [qBeneficiario,setqBeneficiario] = useState("")
  const [qSucursal,setqSucursal] = useState("")
  const [qCatGastos,setqCatGastos] = useState("")
  
  const [listBeneficiario, setlistBeneficiario] = useState([])
  
  const addBeneficiarioList = (type,id=null) => {
    let fil = []
    if (modeEjecutor=="personal") {
      
      fil = nominaData.personal.filter(e=>e.id==gastosBeneficiario)
    }else{
      fil = sucursales.filter(e=>e.id==gastosBeneficiario)
    }
    if (fil.length) {
      let clone = (listBeneficiario)
      if (type=="add") {
        if (!listBeneficiario.filter(e=>e.id==gastosBeneficiario).length) {
          
          setlistBeneficiario(clone.concat(fil[0]))
        }
      }else{
        setlistBeneficiario(clone.filter(e=>e.id!=id))
      }
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
    if (
      gastosDescripcion && 
      gastosCategoria &&
      gastosBanco &&
      gastosFecha
    ) {
      db.saveNewGasto({
        gastosDescripcion,
        gastosCategoria,
        gastosBeneficiario,
        gastosFecha,
        gastosBanco,
        
        gastosMonto: removeMoneda(gastosMonto),
        gastosMonto_dolar: removeMoneda(gastosMonto_dolar),
        gastosTasa,
        selectIdGastos,
        modeMoneda,
        modeEjecutor,
        listBeneficiario,
      }).then(res=>{
        if (res.data.estado) {
          getGastos()
          setNewGastosInput()
          
        }
        notificar(res.data.msj)
      })
    }else{
      alert("Campos Vacíos")
    }
  }
  const getGastos = () => {
    db.getGastos({
      gastosQ,
      gastosQCategoria,
      gastosQFecha,
      gastosQFechaHasta,
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

  const [qvinculacion1General, setqvinculacion1General] = useState("")
  const [qvinculacion2General, setqvinculacion2General] = useState("")
  const [qvinculacion3General, setqvinculacion3General] = useState("")
  const [qvinculacion4General, setqvinculacion4General] = useState("")
  const [qvinculacionmarcaGeneral, setqvinculacionmarcaGeneral] = useState("")
  
  const [datavinculacion1, setdatavinculacion1] = useState([])
  const [datavinculacion2, setdatavinculacion2] = useState([])
  const [datavinculacion3, setdatavinculacion3] = useState([])
  const [datavinculacion4, setdatavinculacion4] = useState([])
  const [datavinculacionmarca, setdatavinculacionmarca] = useState([])

  const [inputselectvinculacion1, setinputselectvinculacion1] = useState("")
  const [inputselectvinculacion2, setinputselectvinculacion2] = useState("")
  const [inputselectvinculacion3, setinputselectvinculacion3] = useState("")
  const [inputselectvinculacion4, setinputselectvinculacion4] = useState("")
  const [inputselectvinculacionmarca, setinputselectvinculacionmarca] = useState("")

  const [inputselectvinculacion1General, setinputselectvinculacion1General] = useState("")
  const [inputselectvinculacion2General, setinputselectvinculacion2General] = useState("")
  const [inputselectvinculacion3General, setinputselectvinculacion3General] = useState("")
  const [inputselectvinculacion4General, setinputselectvinculacion4General] = useState("")
  const [inputselectvinculacionmarcaGeneral, setinputselectvinculacionmarcaGeneral] = useState("")

  const [newNombre1,setnewNombre1] = useState("")
  const [newNombre2,setnewNombre2] = useState("")
  const [newNombre3,setnewNombre3] = useState("")
  const [newNombre4,setnewNombre4] = useState("")
  const [newNombremarca,setnewNombremarca] = useState("")

  const getDatinputSelectVinculacion = () => {
    db.getDatinputSelectVinculacion({}).then(res=>{
      let data = res.data
      setdatavinculacion1(data.datavinculacion1)
      setdatavinculacion2(data.datavinculacion2)
      setdatavinculacion3(data.datavinculacion3)
      setdatavinculacion4(data.datavinculacion4)
      setdatavinculacionmarca(data.datavinculacionmarca)

    })
  }

  const saveCuatroNombres = () => {
    db.saveCuatroNombres({
      selectIdVinculacion,
      inputselectvinculacion1,
      inputselectvinculacion2,
      inputselectvinculacion3,
      inputselectvinculacion4,
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

  const returnCondicion = (condicion) => {

    switch (condicion) {
      case "pagadas":
        return "btn-medsuccess";  
      break;
      case "vencidas":
        return "btn-danger";  
      break;
      case "porvencer":
        return "btn-sinapsis";  
      break;
      case "semipagadas":
        return "btn-primary";  
      break;
      case "abonos":
        return "btn-success";  
      break;
    }

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

          {permiso([1,2]) && viewmainPanel === "nomina" &&
            <NominaHome
              subViewNomina={subViewNomina}
              setsubViewNomina={setsubViewNomina}
            >

              {subViewNomina === "gestion" &&
                <Nomina
                  subViewNominaGestion={subViewNominaGestion}
                  setsubViewNominaGestion={setsubViewNominaGestion}
                >
                  {subViewNominaGestion === "personal" &&
                    <NominaPersonal
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
                      indexSelectNomina={indexSelectNomina}
                      setIndexSelectNomina={setIndexSelectNomina}
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
                      nominapagodetalles={nominapagodetalles}
                      getSucursales={getSucursales}
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

                    >
                    </NominaCargos>
                  }
                </Nomina>
              }
              {subViewNomina === "pagos" &&
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
              }
            </NominaHome>
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
            />
          }
          {permiso([1,2]) && viewmainPanel === "creditos" &&
            <PorCobrar
              fechasMain1={fechasMain1}
              fechasMain2={fechasMain2}
              setfechasMain1={setfechasMain1}
              setfechasMain2={setfechasMain2}
              moneda={moneda}
              getsucursalDetallesData={getsucursalDetallesData}
              sucursalSelect={sucursalSelect}
              setsucursalSelect={setsucursalSelect}
              setsucursalDetallesData={setsucursalDetallesData}
              sucursalDetallesData={sucursalDetallesData}
              getSucursales={getSucursales}
              sucursales={sucursales}
              qestatusaprobaciocaja={qestatusaprobaciocaja}
              setqestatusaprobaciocaja={setqestatusaprobaciocaja}
              aprobarCreditoFun={aprobarCreditoFun}
              subviewpanelsucursales={subviewpanelsucursales}
              setsubviewpanelsucursales={setsubviewpanelsucursales}
              
              />
          }


          {permiso([1]) && viewmainPanel === "compras" &&
            <Compras
              setviewmainPanel={setviewmainPanel}
              viewmainPanel={viewmainPanel}
            />
          }
          {permiso([1,2,3,6]) && viewmainPanel === "auditoria" &&
            <Auditoria
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
              setbancosdata={setbancosdata}
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
              getCatGeneralFun={getCatGeneralFun}
              getCatCajas={getCatCajas}
              sucursales={sucursales}

              cuentasPagosDescripcion={cuentasPagosDescripcion}
              setcuentasPagosDescripcion={setcuentasPagosDescripcion}
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
              categoriaMovBanco={categoriaMovBanco}
              number={number}
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

          {permiso([1,2,4,8]) && viewmainPanel === "efectivo" &&
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
              

              {subviewpanelsucursales === "aprobacioncajafuerte" &&
                <>
                  <FechasMain
                    fechasMain1={fechasMain1}
                    fechasMain2={fechasMain2}
                    setfechasMain1={setfechasMain1}
                    setfechasMain2={setfechasMain2}
                  />
                  <AprobacionCajaFuerte
                    moneda={moneda}
                    getsucursalDetallesData={getsucursalDetallesData}
                    sucursalSelect={sucursalSelect}
                    setsucursalSelect={setsucursalSelect}
                    setsucursalDetallesData={setsucursalDetallesData}
                    sucursalDetallesData={sucursalDetallesData}

                    getSucursales={getSucursales}
                    sucursales={sucursales}
                    qestatusaprobaciocaja={qestatusaprobaciocaja}
                    setqestatusaprobaciocaja={setqestatusaprobaciocaja}
                    aprobarMovCajaFuerte={aprobarMovCajaFuerte}
                  >
                  </AprobacionCajaFuerte>
                </>
              }
              {subviewpanelsucursales === "cuentasporpagar" ?
                <>
                  {subViewCuentasxPagar === "detallado"?
                  <>
                    {cuentasporpagarDetallesView=="cuentas"?
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
                      efectivoDisponibleSucursalesData={efectivoDisponibleSucursalesData}
                      setefectivoDisponibleSucursalesData={setefectivoDisponibleSucursalesData}
                      getDisponibleEfectivoSucursal={getDisponibleEfectivoSucursal}
                      colorSucursal={colorSucursal}
                      moneda={moneda}
                    />:null
                  }
                </>
              :null
              }

            </Efectivo>
          }

          {permiso([1,2,5]) && viewmainPanel === "gastos" && 
            <Gastos
              setlistBeneficiario={setlistBeneficiario}
              addBeneficiarioList={addBeneficiarioList}
              listBeneficiario={listBeneficiario}
              modeMoneda={modeMoneda}
              setmodeMoneda={setmodeMoneda}
              modeEjecutor={modeEjecutor}
              setmodeEjecutor={setmodeEjecutor}
              formatAmount={formatAmount}
              nominaData={nominaData}

              categoriaMovBanco={categoriaMovBanco}
              gastosData={gastosData}
              setgastosData={setgastosData}
              gastosQ={gastosQ}
              setgastosQ={setgastosQ}
              gastosQCategoria={gastosQCategoria}
              setgastosQCategoria={setgastosQCategoria}
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

          {permiso([1]) && viewmainPanel === "enviar" &&
            <Pedidos >
              
            </Pedidos>
          }


          {permiso([1]) && viewmainPanel === "inventario" &&
            <>
              <NavInventario
                subViewInventario={subViewInventario}
                setsubViewInventario={setsubViewInventario}
              />
              {subViewInventario == "gestion" ?
                <GestionInventario
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
                  proveedoresList={proveedoresList}
                  number={number}
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


          {permiso([1,2,3,5,7,8]) && viewmainPanel === "sucursales" &&
            <PanelSucursales
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
              getCatGeneralFun={getCatGeneralFun}
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
              getsucursalDetallesData={getsucursalDetallesData}
              sucursalDetallesData={sucursalDetallesData}
              subviewpanelsucursales={subviewpanelsucursales}
              setsubviewpanelsucursales={setsubviewpanelsucursales}
              moneda={moneda}

            />
          }


        </Panel>
      </>}
    </>
  );
}
render(<Home />, document.getElementById('app'));

