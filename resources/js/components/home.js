import { useHotkeys } from 'react-hotkeys-hook';


import { useState, useEffect, useRef, StrictMode } from 'react';
import ReactDOM, { render } from 'react-dom';
import db from '../database/database';
import Header from './header';
import SelectSucursal from './selectSucursal';

import VentasComponent from './ventas';
import GastosComponent from './gastos';
import InventarioComponent from './inventario';

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

import Gastos from './panel/gastos'
import SucursalDetallesGastos from './panel/sucursaldetallesgastos'
import SucursalListGastos from './panel/sucursallistgastos'

import Inventario from './panel/inventario'
import GestionInventario from './panel/gestioninventario'
import Garantias from './panel/garantias'
import Pedidos from '../components/pedidos';
import FallasComponent from './fallas';

import NominaHome from './nomina/index';

import Nomina from './nomina/nomina';

import NominaCargos from './nomina/nominacargos';
import NominaPersonal from './nomina/nominapersonal';

import NominaPagos from './nomina/nominapagos';







function Home() {
  // ///In Last//////
  const [view, setView] = useState("")


  const [fallas, setfallas] = useState([])
  const [gastos, setgastos] = useState([])
  const [ventas, setventas] = useState([])

  const [selectgastos, setselectgastos] = useState("*")
  const [fechaGastos, setfechaGastos] = useState("")

  const [tipogasto, settipogasto] = useState(1)

  const [selectfechaventa, setselectfechaventa] = useState("")



  ///////////Inventario
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

  // const [qBuscarProveedor,setQBuscarProveedor] = useState("")

  // const [proveedoresList,setProveedoresList] = useState([])

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

/*   useEffect(() => {
    getFallas()
  }, [
    qFallas,
    orderCatFallas,
    orderSubCatFallas,
    ascdescFallas
  ])

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
  const [categorias, setcategorias] = useState([])

  const [productosInventario, setProductosInventario] = useState([])

  const [qBuscarInventario, setQBuscarInventario] = useState("")
  const [indexSelectInventario, setIndexSelectInventario] = useState(null)


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

  const [qBuscarProveedor, setQBuscarProveedor] = useState("")

  const [proveedoresList, setProveedoresList] = useState([])

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

  const setInputsInventario = () => {
    if (productosInventario[indexSelectInventario]) {
      let obj = productosInventario[indexSelectInventario]
      setinpInvbarras(obj.codigo_barras ? obj.codigo_barras : "")
      setinpInvcantidad(obj.cantidad ? obj.cantidad : "")
      setinpInvalterno(obj.codigo_proveedor ? obj.codigo_proveedor : "")
      setinpInvunidad(obj.unidad ? obj.unidad : "")
      setinpInvdescripcion(obj.descripcion ? obj.descripcion : "")
      setinpInvbase(obj.precio_base ? obj.precio_base : "")
      setinpInvventa(obj.precio ? obj.precio : "")
      setinpInviva(obj.iva ? obj.iva : "")

      setinpInvcategoria(obj.id_categoria ? obj.id_categoria : "")
      setinpInvid_proveedor(obj.id_proveedor ? obj.id_proveedor : "")
      setinpInvid_marca(obj.id_marca ? obj.id_marca : "")
      setinpInvid_deposito(obj.id_deposito ? obj.id_deposito : "")

      setinpInvLotes(obj.lotes ? obj.lotes : [])

    }
  }
  const setNewProducto = () => {
    setIndexSelectInventario(null)
    setinpInvbarras("")
    setinpInvcantidad("")
    setinpInvalterno("")
    setinpInvunidad("UND")
    setinpInvdescripcion("")
    setinpInvbase("")
    setinpInvventa("")
    setinpInviva("0")

    setinpInvLotes([])

    if (facturas[factSelectIndex]) {
      setinpInvid_proveedor(facturas[factSelectIndex].proveedor.id)
    }


    setinpInvid_marca("GENÉRICO")
    setinpInvid_deposito(1)
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
      e.unidad == "" ||
      e.id_proveedor == "" ||
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
          id_categoria: "40",
          id_marca: "",
          unidad: "UND",
          id_proveedor: pro,
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
      e.id_proveedor == "" ||
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
    getGastos()
  }, [fechaGastos])

  useEffect(() => {
    getFacturas()
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
  ])

  useEffect(() => {
    setInputsInventario()
  }, [indexSelectInventario])

  useEffect(() => {
    setInputsProveedores()
  }, [indexSelectProveedores])

  useEffect(() => {
    if (view == "fallas") {
      getFallas()

    } else if (view == "gastos") {
      getGastos()
    } else if (view == "ventas") {
      getVentas()
    }
  }, [view])


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
    if (val == "") return ""
    return val.replace(/[^\d|\.]+/g, '')
  }
  const loginRes = res => {
    notificar(res)
    if (res.data) {
      setLoginActive(res.data.estado)
    }
  }

  const notificar = (msj, fixed = true, simple=false) => {
    if (fixed) {
        setTimeout(() => {
            setMsj("")
        }, 3000)
    }else{
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
        }else if(typeof msj === 'string' || msj instanceof String){
            setMsj(msj)
        }

    }
  }


  ///////////Inventario

  const getGastos = () => {
    setLoading(true)

    if (sucursales.filter(e => e.char == sucursalSelect).length) {
      db.getGastos({ fechaGastos, id_sucursal: sucursales.filter(e => e.char == sucursalSelect)[0].id }).then(res => {
        setgastos(res.data)
        setLoading(false)
      })

    }
  }

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
  const [viewmainPanel, setviewmainPanel] = useState("panel")

  const [subViewInventario, setsubViewInventario] = useState("gestion")
 
  const [msj, setMsj] = useState("")
  const [loading, setLoading] = useState(false)
  const [loginActive, setLoginActive] = useState(false)
  const [sucursalSelect, setsucursalSelect] = useState(null)

  const [sucursales, setsucursales] = useState([])
  const [sucursalListData, setsucursalListData] = useState([])
  const [sucursalDetallesData, setsucursalDetallesData] = useState({})

  const [fechasMain1, setfechasMain1] = useState("")
  const [fechasMain2, setfechasMain2] = useState("")
  const [filtros, setfiltros] = useState({})
  

  useEffect(() => {
    getSucursales()
    getToday()
    
  }, [])
  useEffect(() => {
    getsucursalListData()
  }, [viewmainPanel,fechasMain1,fechasMain2])

  useEffect(() => {
    getsucursalDetallesData()
  }, [sucursalSelect,viewmainPanel,fechasMain1,fechasMain2])

  
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
    })
  }
  const getSucursales = () => {
    setLoading(true)
    db.getSucursales({}).then(res => {
      setsucursales(res.data)
      setLoading(false)
    })
  }
  const getsucursalListData = () => {
    setLoading(true)
    db.getsucursalListData({
      fechasMain1,
      fechasMain2,
      filtros,

      viewmainPanel,
    }).then(res => {
      setsucursalListData(res.data)
      setLoading(false)
    })
  }
  const getsucursalDetallesData = () => {
    setLoading(true)
    db.getsucursalDetallesData({
      fechasMain1,
      fechasMain2,
      filtros,

      viewmainPanel,
      sucursalSelect,
    }).then(res => {
      setsucursalDetallesData(res.data)
      setLoading(false)
    })
  }

  /// Nomina ///
  const [subViewNomina, setsubViewNomina] = useState("gestion")
  const [subViewNominaGestion, setsubViewNominaGestion] = useState("gestion")
  
  const [nominaNombre,setnominaNombre] = useState("")
  const [nominaCedula,setnominaCedula] = useState("")
  const [nominaTelefono,setnominaTelefono] = useState("")
  const [nominaDireccion,setnominaDireccion] = useState("")
  const [nominaFechadeNacimiento,setnominaFechadeNacimiento] = useState("")
  const [nominaFechadeIngreso,setnominaFechadeIngreso] = useState("")
  const [nominaGradoInstruccion,setnominaGradoInstruccion] = useState("")
  const [nominaCargo,setnominaCargo] = useState("")
  const [nominaSucursal,setnominaSucursal] = useState("")

  const [indexSelectNomina,setIndexSelectNomina] = useState(null)
  const [qNomina,setqNomina] = useState("")
  const [qSucursalNomina,setqSucursalNomina] = useState("")
  const [qCargoNomina,setqCargoNomina] = useState("")
  
  const [nominaData,setnominaData] = useState([])
  
  const [nominapagodetalles,setnominapagodetalles] = useState({})

  
  const selectNominaDetalles = id => {
    setnominapagodetalles({})
    let personal = nominaData.personal 
    if (personal) {
      let nomina = personal.filter(e=>e.id===id)
      if (nomina) {
        setnominapagodetalles(nomina[0])
      }  
    } 
  }

  const delPersonalNomina = event =>{
    event.preventDefault()
    db.delPersonalNomina({
      id:indexSelectNomina
    }).then(({data})=>{
      if (data.estado) {
        getPersonalNomina()
      }
      notificar(data.msj)
    })
  }
  const addPersonalNomina = event =>{
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

      id:indexSelectNomina
    }).then(({data})=>{
      if (data.estado) {
        getPersonalNomina()
      }
      notificar(data.msj)
    })
  }
  const getPersonalNomina = event =>{
    if (event) {
      event.preventDefault()
    }
    db.getPersonalNomina({
      fechasMain1,
      fechasMain2,
      qNomina,
      qSucursalNomina,
      qCargoNomina,
      type:subViewNomina
    }).then(({data})=>{
      setnominaData(data)
    })
  }
  
  ////Cargos

  const [cargosDescripcion,setcargosDescripcion] = useState("")
  const [cargosSueldo,setcargosSueldo] = useState("")
  const [qCargos,setqCargos] = useState("")
  const [indexSelectCargo,setindexSelectCargo] = useState(null)

  const [cargosData,setcargosData] = useState([])

  const delPersonalCargos = () =>{
    db.delPersonalCargos({
      id:indexSelectCargo
    }).then(({data})=>{
      if (data.estado) {
        getPersonalCargos()
      }
      notificar(data.msj)
    })
  }
  const addPersonalCargos = event =>{
    event.preventDefault()
    db.setPersonalCargos({
      cargosDescripcion,
      cargosSueldo,
      id:indexSelectCargo,
    }).then(({data})=>{
      if (data.estado) {
        getPersonalCargos(null)
      }
      notificar(data.msj)

    })
  }
  const getPersonalCargos = event =>{
    if (event) {
      event.preventDefault()
    }

    db.getPersonalCargos({
      qCargos
    }).then(({data})=>{
      setcargosData(data)
    })
  }


  return (
    <>
      {!loginActive ? <Login loginRes={loginRes} /> : <>
        {msj != "" ? <Notificacion msj={msj} notificar={notificar} /> : null}

        {loading ? <Cargando active={loading} /> : null}

        {/* 
        <Toplabel
          sucursales={sucursales}
          sucursalSelect={sucursalSelect}
        />
        <div className="container marginb-6 margint-6 p-0">
          {sucursalSelect === null ?
            <SelectSucursal
              setsucursalSelect={setsucursalSelect}
              sucursalSelect={sucursalSelect}
              sucursales={sucursales}
              viewProductos={viewProductos}
              setviewProductos={setviewProductos}
            />
            : null}

          {selectView("fallas") ? <FallasComponent
            fallas={fallas}
          /> : null}
          {selectView("gastos") ? <GastosComponent
            gastos={gastos}
            selectgastos={selectgastos}
            setselectgastos={setselectgastos}
            setfechaGastos={setfechaGastos}
            fechaGastos={fechaGastos}
            tipogasto={tipogasto}
            settipogasto={settipogasto}
            moneda={moneda}
          /> : null}
          {selectView("ventas") ? <VentasComponent
            ventas={ventas}
            selectfechaventa={selectfechaventa}
            setselectfechaventa={setselectfechaventa}
            moneda={moneda}
          /> : null}


          {sucursalSelect === "inventario" ? <InventarioComponent
            showPedidoBarras={showPedidoBarras}
            productosInventario={productosInventario}
            qBuscarInventario={qBuscarInventario}
            setQBuscarInventario={setQBuscarInventario}
            setIndexSelectInventario={setIndexSelectInventario}
            indexSelectInventario={indexSelectInventario}
            inputBuscarInventario={inputBuscarInventario}

            inpInvbarras={inpInvbarras}
            setinpInvbarras={setinpInvbarras}
            inpInvcantidad={inpInvcantidad}
            setinpInvcantidad={setinpInvcantidad}
            inpInvalterno={inpInvalterno}
            setinpInvalterno={setinpInvalterno}
            inpInvunidad={inpInvunidad}
            setinpInvunidad={setinpInvunidad}
            inpInvcategoria={inpInvcategoria}
            setinpInvcategoria={setinpInvcategoria}
            inpInvdescripcion={inpInvdescripcion}
            setinpInvdescripcion={setinpInvdescripcion}
            inpInvbase={inpInvbase}
            setinpInvbase={setinpInvbase}
            inpInvventa={inpInvventa}
            setinpInvventa={setinpInvventa}
            inpInviva={inpInviva}
            setinpInviva={setinpInviva}

            guardarNuevoProducto={guardarNuevoProducto}

            setProveedor={setProveedor}
            proveedordescripcion={proveedordescripcion}
            setproveedordescripcion={setproveedordescripcion}
            proveedorrif={proveedorrif}
            setproveedorrif={setproveedorrif}
            proveedordireccion={proveedordireccion}
            setproveedordireccion={setproveedordireccion}
            proveedortelefono={proveedortelefono}
            setproveedortelefono={setproveedortelefono}

            subViewInventario={subViewInventario}
            setsubViewInventario={setsubViewInventario}

            setIndexSelectProveedores={setIndexSelectProveedores}
            indexSelectProveedores={indexSelectProveedores}
            qBuscarProveedor={qBuscarProveedor}
            setQBuscarProveedor={setQBuscarProveedor}
            proveedoresList={proveedoresList}

            delProveedor={delProveedor}
            delProducto={delProducto}

            inpInvid_proveedor={inpInvid_proveedor}
            setinpInvid_proveedor={setinpInvid_proveedor}
            inpInvid_marca={inpInvid_marca}
            setinpInvid_marca={setinpInvid_marca}
            inpInvid_deposito={inpInvid_deposito}
            setinpInvid_deposito={setinpInvid_deposito}

            depositosList={depositosList}

            facturas={facturas}

            factqBuscar={factqBuscar}
            setfactqBuscar={setfactqBuscar}
            factqBuscarDate={factqBuscarDate}
            setfactqBuscarDate={setfactqBuscarDate}
            factsubView={factsubView}
            setfactsubView={setfactsubView}
            factSelectIndex={factSelectIndex}
            setfactSelectIndex={setfactSelectIndex}
            factOrderBy={factOrderBy}
            setfactOrderBy={setfactOrderBy}
            factOrderDescAsc={factOrderDescAsc}
            setfactOrderDescAsc={setfactOrderDescAsc}
            factInpid_proveedor={factInpid_proveedor}
            setfactInpid_proveedor={setfactInpid_proveedor}
            factInpnumfact={factInpnumfact}
            setfactInpnumfact={setfactInpnumfact}
            factInpdescripcion={factInpdescripcion}
            setfactInpdescripcion={setfactInpdescripcion}
            factInpmonto={factInpmonto}
            setfactInpmonto={setfactInpmonto}
            factInpfechavencimiento={factInpfechavencimiento}
            setfactInpfechavencimiento={setfactInpfechavencimiento}

            factInpestatus={factInpestatus}
            setfactInpestatus={setfactInpestatus}

            setFactura={setFactura}
            delFactura={delFactura}

            Invnum={Invnum}
            setInvnum={setInvnum}
            InvorderColumn={InvorderColumn}
            setInvorderColumn={setInvorderColumn}
            InvorderBy={InvorderBy}
            setInvorderBy={setInvorderBy}
            delItemFact={delItemFact}

            subviewProveedores={subviewProveedores}
            setsubviewProveedores={setsubviewProveedores}

            subviewCargarProductos={subviewCargarProductos}
            setsubviewCargarProductos={setsubviewCargarProductos}
            viewProductos={viewProductos}
            setviewProductos={setviewProductos}

            indexSelectCarrito={indexSelectCarrito}
            setindexSelectCarrito={setindexSelectCarrito}

            showCantidadCarritoFun={showCantidadCarritoFun}
            showCantidadCarrito={showCantidadCarrito}
            setshowCantidadCarrito={setshowCantidadCarrito}

            sucursales={sucursales}
            ctSucursales={ctSucursales}
            setctSucursales={setctSucursales}

            setCarrito={setCarrito}

            pedidoList={pedidoList}
            id_pedido={id_pedido}
            setid_pedido={setid_pedido}

            qpedido={qpedido}
            setqpedido={setqpedido}
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

            setDelCarrito={setDelCarrito}
            setCtCarrito={setCtCarrito}
            setProdCarritoInterno={setProdCarritoInterno}
            sendPedidoSucursal={sendPedidoSucursal}

            openReporteFalla={openReporteFalla}
            getPagoProveedor={getPagoProveedor}
            setPagoProveedor={setPagoProveedor}
            pagosproveedor={pagosproveedor}
            tipopagoproveedor={tipopagoproveedor}
            settipopagoproveedor={settipopagoproveedor}
            montopagoproveedor={montopagoproveedor}
            setmontopagoproveedor={setmontopagoproveedor}
            setmodFact={setmodFact}
            modFact={modFact}
            saveFactura={saveFactura}
            categorias={categorias}
            setporcenganancia={setporcenganancia}
            refsInpInvList={refsInpInvList}
            guardarNuevoProductoLote={guardarNuevoProductoLote}
            changeInventario={changeInventario}
            reporteInventario={reporteInventario}
            addNewLote={addNewLote}
            changeModLote={changeModLote}
            modViewInventario={modViewInventario}
            setmodViewInventario={setmodViewInventario}
            setNewProducto={setNewProducto}
            verDetallesFactura={verDetallesFactura}
            showaddpedidocentral={showaddpedidocentral}
            setshowaddpedidocentral={setshowaddpedidocentral}
            valheaderpedidocentral={valheaderpedidocentral}
            setvalheaderpedidocentral={setvalheaderpedidocentral}
            valbodypedidocentral={valbodypedidocentral}
            setvalbodypedidocentral={setvalbodypedidocentral}
            procesarImportPedidoCentral={procesarImportPedidoCentral}
            moneda={moneda}
            productosInventario={productosInventario}
            qBuscarInventario={qBuscarInventario}
            setQBuscarInventario={setQBuscarInventario}
            setIndexSelectInventario={setIndexSelectInventario}
            indexSelectInventario={indexSelectInventario}
            inputBuscarInventario={inputBuscarInventario}
            inpInvbarras={inpInvbarras}
            setinpInvbarras={setinpInvbarras}
            inpInvcantidad={inpInvcantidad}
            setinpInvcantidad={setinpInvcantidad}
            inpInvalterno={inpInvalterno}
            setinpInvalterno={setinpInvalterno}
            inpInvunidad={inpInvunidad}
            setinpInvunidad={setinpInvunidad}
            inpInvcategoria={inpInvcategoria}
            setinpInvcategoria={setinpInvcategoria}
            inpInvdescripcion={inpInvdescripcion}
            setinpInvdescripcion={setinpInvdescripcion}
            inpInvbase={inpInvbase}
            setinpInvbase={setinpInvbase}
            inpInvventa={inpInvventa}
            setinpInvventa={setinpInvventa}
            inpInviva={inpInviva}
            setinpInviva={setinpInviva}
            inpInvLotes={inpInvLotes}
            number={number}
            guardarNuevoProducto={guardarNuevoProducto}
            setProveedor={setProveedor}
            proveedordescripcion={proveedordescripcion}
            setproveedordescripcion={setproveedordescripcion}
            proveedorrif={proveedorrif}
            setproveedorrif={setproveedorrif}
            proveedordireccion={proveedordireccion}
            setproveedordireccion={setproveedordireccion}
            proveedortelefono={proveedortelefono}
            setproveedortelefono={setproveedortelefono}
            subViewInventario={subViewInventario}
            setsubViewInventario={setsubViewInventario}
            setIndexSelectProveedores={setIndexSelectProveedores}
            indexSelectProveedores={indexSelectProveedores}
            qBuscarProveedor={qBuscarProveedor}
            setQBuscarProveedor={setQBuscarProveedor}
            proveedoresList={proveedoresList}
            delProveedor={delProveedor}
            delProducto={delProducto}
            inpInvid_proveedor={inpInvid_proveedor}
            setinpInvid_proveedor={setinpInvid_proveedor}
            inpInvid_marca={inpInvid_marca}
            setinpInvid_marca={setinpInvid_marca}
            inpInvid_deposito={inpInvid_deposito}
            setinpInvid_deposito={setinpInvid_deposito}
            depositosList={depositosList}
            marcasList={marcasList}
            setshowModalFacturas={setshowModalFacturas}
            showModalFacturas={showModalFacturas}
            facturas={facturas}
            factqBuscar={factqBuscar}
            setfactqBuscar={setfactqBuscar}
            factqBuscarDate={factqBuscarDate}
            setfactqBuscarDate={setfactqBuscarDate}
            factsubView={factsubView}
            setfactsubView={setfactsubView}
            factSelectIndex={factSelectIndex}
            setfactSelectIndex={setfactSelectIndex}
            factOrderBy={factOrderBy}
            setfactOrderBy={setfactOrderBy}
            factOrderDescAsc={factOrderDescAsc}
            setfactOrderDescAsc={setfactOrderDescAsc}
            factInpid_proveedor={factInpid_proveedor}
            setfactInpid_proveedor={setfactInpid_proveedor}
            factInpnumfact={factInpnumfact}
            setfactInpnumfact={setfactInpnumfact}
            factInpdescripcion={factInpdescripcion}
            setfactInpdescripcion={setfactInpdescripcion}
            factInpmonto={factInpmonto}
            setfactInpmonto={setfactInpmonto}
            factInpfechavencimiento={factInpfechavencimiento}
            setfactInpfechavencimiento={setfactInpfechavencimiento}
            factInpestatus={factInpestatus}
            setfactInpestatus={setfactInpestatus}
            setFactura={setFactura}
            delFactura={delFactura}
            Invnum={Invnum}
            setInvnum={setInvnum}
            InvorderColumn={InvorderColumn}
            setInvorderColumn={setInvorderColumn}
            InvorderBy={InvorderBy}
            setInvorderBy={setInvorderBy}
            delItemFact={delItemFact}
            qFallas={qFallas}
            setqFallas={setqFallas}
            orderCatFallas={orderCatFallas}
            setorderCatFallas={setorderCatFallas}
            orderSubCatFallas={orderSubCatFallas}
            setorderSubCatFallas={setorderSubCatFallas}
            ascdescFallas={ascdescFallas}
            setascdescFallas={setascdescFallas}
            fallas={fallas}
            delFalla={delFalla}
            getPedidosCentral={getPedidosCentral}
            selectPedidosCentral={selectPedidosCentral}
            checkPedidosCentral={checkPedidosCentral}
            pedidosCentral={pedidosCentral}
            setIndexPedidoCentral={setIndexPedidoCentral}
            indexPedidoCentral={indexPedidoCentral}
            fechaQEstaInve={fechaQEstaInve}
            setfechaQEstaInve={setfechaQEstaInve}
            fechaFromEstaInve={fechaFromEstaInve}
            setfechaFromEstaInve={setfechaFromEstaInve}
            fechaToEstaInve={fechaToEstaInve}
            setfechaToEstaInve={setfechaToEstaInve}
            orderByEstaInv={orderByEstaInv}
            setorderByEstaInv={setorderByEstaInv}
            orderByColumEstaInv={orderByColumEstaInv}
            setorderByColumEstaInv={setorderByColumEstaInv}
            dataEstaInven={dataEstaInven}
          /> : null}
        </div> */}
        <Panel>
          <Header
            viewmainPanel={viewmainPanel}
            setviewmainPanel={setviewmainPanel}
            sucursalSelect={sucursalSelect}
            setsucursalSelect={setsucursalSelect}
            sucursales={sucursales}

          />
          <FechasMain 
            fechasMain1={fechasMain1}
            fechasMain2={fechasMain2}
            setfechasMain1={setfechasMain1}
            setfechasMain2={setfechasMain2}
          />
          {viewmainPanel === "panel" && 
            <PanelOpciones
              viewmainPanel={viewmainPanel}
              setviewmainPanel={setviewmainPanel}
            />
          }


          {viewmainPanel === "cierres" && 
          <Cierres>
            <BalanceCierres
            
            />

            {sucursalSelect?
              <SucursalDetallesCierres
                sucursalDetallesData={sucursalDetallesData}

              /> 
            :
              <SucursalListCierres
                sucursalListData={sucursalListData}

                sucursalSelect={sucursalSelect}
                setsucursalSelect={setsucursalSelect}
              />  
            }
          </Cierres>
          }
          {viewmainPanel === "inventario" && 
          <Inventario
            subViewInventario={subViewInventario}
            setsubViewInventario={setsubViewInventario}
            >
            {subViewInventario==="gestion"?
              <GestionInventario>

              </GestionInventario>
            :null}

            {subViewInventario==="garantia"?
              <Garantias>

              </Garantias>
            :null}

            {subViewInventario==="pedidos"?
              <Pedidos
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
                setid_pedido={setid_pedido}
                id_pedido={id_pedido}
      
                qpedido={qpedido}
                setqpedido={setqpedido}
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
              />
            :null}

            {subViewInventario==="fallas"?
              <FallasComponent>

              </FallasComponent>
            :null}
          </Inventario>
          }
          {viewmainPanel === "gastos" && 
            <Gastos>
              {sucursalSelect?
                <SucursalDetallesGastos
                  sucursalDetallesData={sucursalDetallesData}

                /> 
              :
                <SucursalListGastos
                  sucursalListData={sucursalListData}

                  sucursalSelect={sucursalSelect}
                  setsucursalSelect={setsucursalSelect}
                />  
              }
            </Gastos>
          }
           {viewmainPanel === "nomina" && 
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
              </Nomina> }
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

                >
                </NominaPagos>
              }
            </NominaHome>
          }
          
        </Panel>
      </>}
    </>
  );
}
render(<Home />, document.getElementById('app'));

