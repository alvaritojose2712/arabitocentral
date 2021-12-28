import { useHotkeys } from 'react-hotkeys-hook';


import {useState,useEffect, useRef,StrictMode} from 'react';
import ReactDOM, {render} from 'react-dom';
import db from '../database/database';
import Header from './header';
import SelectSucursal from './selectSucursal';

import FallasComponent from './fallas';
import VentasComponent from './ventas';
import GastosComponent from './gastos';
import InventarioComponent from './inventario';

import Notificacion from '../components/notificacion';

import Cargando from '../components/cargando';


import Toplabel from './toplabel';


function Home() {
  const [msj,setMsj] = useState("")
  const [view,setView] = useState("")
  const [loading,setLoading] = useState(false)

  const [sucursales,setsucursales] = useState([])
  const [sucursalSelect,setsucursalSelect] = useState(null)

  const [fallas,setfallas] = useState([])
  const [gastos,setgastos] = useState([])
  const [ventas,setventas] = useState([])

  const [selectgastos,setselectgastos] = useState("*")
  const [fechaGastos,setfechaGastos] = useState("")

  const [tipogasto,settipogasto] = useState(1)

  const [selectfechaventa,setselectfechaventa] = useState("")



  ///////////Inventario
  const inputBuscarInventario = useRef(null)

  const [productosInventario,setProductosInventario] = useState([])
  const [qBuscarInventario,setQBuscarInventario] = useState("")
  const [indexSelectInventario,setIndexSelectInventario] = useState(null)

  const [inpInvbarras,setinpInvbarras] = useState("")
  const [inpInvcantidad,setinpInvcantidad] = useState("")
  const [inpInvalterno,setinpInvalterno] = useState("")
  const [inpInvunidad,setinpInvunidad] = useState("UND")
  const [inpInvcategoria,setinpInvcategoria] = useState("24")
  const [inpInvdescripcion,setinpInvdescripcion] = useState("")
  const [inpInvbase,setinpInvbase] = useState("")
  const [inpInvventa,setinpInvventa] = useState("")
  const [inpInviva,setinpInviva] = useState("0")

  const [inpInvid_proveedor,setinpInvid_proveedor] = useState("")
  const [inpInvid_marca,setinpInvid_marca] = useState("")
  const [inpInvid_deposito,setinpInvid_deposito] = useState("")
  const [inpInvporcentaje_ganancia,setinpInvporcentaje_ganancia] = useState(0)
      


  const [proveedordescripcion,setproveedordescripcion] = useState("")
  const [proveedorrif,setproveedorrif] = useState("")
  const [proveedordireccion,setproveedordireccion] = useState("")
  const [proveedortelefono,setproveedortelefono] = useState("")

  const [subViewInventario,setsubViewInventario] = useState("inventario")

  const [indexSelectProveedores,setIndexSelectProveedores] = useState(null)

  const [qBuscarProveedor,setQBuscarProveedor] = useState("")

  const [proveedoresList,setProveedoresList] = useState([])

  const [depositosList,setdepositosList] = useState([])

  const [facturas,setfacturas] = useState([])

  const [factqBuscar,setfactqBuscar] = useState("")
  const [factqBuscarDate,setfactqBuscarDate] = useState("")
  const [factOrderBy,setfactOrderBy] = useState("id")
  const [factOrderDescAsc,setfactOrderDescAsc] = useState("desc")
  const [factsubView,setfactsubView] = useState("buscar")
  const [factSelectIndex,setfactSelectIndex] = useState(null)
  const [factInpid_proveedor,setfactInpid_proveedor] = useState("")
  const [factInpnumfact,setfactInpnumfact] = useState("")
  const [factInpdescripcion,setfactInpdescripcion] = useState("")
  const [factInpmonto,setfactInpmonto] = useState("")
  const [factInpfechavencimiento,setfactInpfechavencimiento] = useState("")

  const [factInpestatus,setfactInpestatus] = useState(0)

  const [Invnum,setInvnum] = useState(25)
  const [InvorderColumn,setInvorderColumn] = useState("id")
  const [InvorderBy,setInvorderBy] = useState("desc")

  const [subviewProveedores,setsubviewProveedores] = useState("buscar")
  const [subviewCargarProductos,setsubviewCargarProductos] = useState("buscar")

  useEffect(()=>{
    getToday()
    getSucursales()
    getProveedores()
  },[])

  useEffect(()=>{
    getVentas()
  },[selectfechaventa])


  useEffect(()=>{
    getGastos()
  },[fechaGastos])
  useEffect(()=>{
    getFacturas()
  },[
  factqBuscar,
  factqBuscarDate,
  factOrderBy,
  factOrderDescAsc
  ])

  useEffect(()=>{
    buscarInventario()
  },[
    Invnum,
    InvorderColumn,
    InvorderBy,
    qBuscarInventario,
  ])

   useEffect(()=>{
    setInputsInventario()
  },[indexSelectInventario])


  useEffect(()=>{
    if (view=="fallas") {
      getFallas()

    }else if (view=="gastos") {
      getGastos()
    }else if (view=="ventas") {
      getVentas()
    }
  },[view])

   useEffect(()=>{
    setInputsProveedores()
  },[indexSelectProveedores])

  const moneda = (value, decimals=2, separators=['.',".",',']) => {
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
  const number = (val) =>{
    if (val=="") return ""
    return val.replace(/[^\d|\.]+/g,'')
  }
  const notificar = (msj,fixed=true) => {
    if (fixed) {
      setTimeout(()=>{
        setMsj("")
      },3000)
    }
    if (msj=="") {
      setMsj("")
    }else{
      if (msj.data) {
        if (msj.data.msj) {
          setMsj(msj.data.msj)

        }else{

          setMsj(JSON.stringify(msj.data))
        }
      }

    }
  }


  ///////////Inventario
  const setInputsProveedores = () =>{
    if (proveedoresList[indexSelectProveedores]) {
      let obj = proveedoresList[indexSelectProveedores]

      setproveedordescripcion(obj.descripcion)
      setproveedorrif(obj.rif)
      setproveedordireccion(obj.direccion)
      setproveedortelefono(obj.telefono)
    }
  }

  const setInputsInventario = () =>{
    if (productosInventario[indexSelectInventario]) {
      let obj = productosInventario[indexSelectInventario]
      setinpInvbarras(obj.codigo_barras)
      setinpInvcantidad(obj.cantidad)
      setinpInvalterno(obj.codigo_proveedor)
      setinpInvunidad(obj.unidad)
      setinpInvdescripcion(obj.descripcion)
      setinpInvbase(obj.precio_base)
      setinpInvventa(obj.precio)
      setinpInviva(obj.iva)

      setinpInvcategoria(obj.id_categoria)
      setinpInvid_proveedor(obj.id_proveedor)
      setinpInvid_marca(obj.id_marca)
      setinpInvid_deposito(obj.id_deposito)

    }
  }

   const getToday = () =>{
    db.today({}).then(res=>{
      let today = res.data
      setfechaGastos(today)
      setselectfechaventa(today)
      setfactqBuscarDate(today)

    })
  }


  const getSucursales = () => {
    setLoading(true)
    db.getSucursales().then(res=>{
      setsucursales(res.data)
      setLoading(false)
    })
  }

  const getFallas = () => {
    setLoading(true)

    if (sucursales.filter(e=>e.char==sucursalSelect).length) {
      db.getFallas({id_sucursal: sucursales.filter(e=>e.char==sucursalSelect)[0].id }).then(res=>{
        setfallas(res.data)
        setLoading(false)
      })

    }
  }

  const getGastos = () => {
    setLoading(true)

    if (sucursales.filter(e=>e.char==sucursalSelect).length) {
      db.getGastos({fechaGastos, id_sucursal: sucursales.filter(e=>e.char==sucursalSelect)[0].id }).then(res=>{
        setgastos(res.data)
        setLoading(false)
      })

    }
  }

  const getVentas = () => {
    setLoading(true)

    if (sucursales.filter(e=>e.char==sucursalSelect).length) {
      db.getVentas({selectfechaventa,id_sucursal: sucursales.filter(e=>e.char==sucursalSelect)[0].id }).then(res=>{
        setventas(res.data)
        setLoading(false)
      })

    }
  }
  const buscarInventario = e => {
    setLoading(true)
    db.getinventario({
      num:Invnum,
      itemCero:true,
      qProductosMain:qBuscarInventario,
      orderColumn:InvorderColumn,
      orderBy:InvorderBy
    }).then(res=>{
      setProductosInventario(res.data)
      setLoading(false)
      setIndexSelectInventario(null)
      if (res.data.length===1) {
        setIndexSelectInventario(0)
      }else if(res.data.length==0){
        setinpInvbarras(qBuscarInventario)
      }
    })
  }

  const guardarNuevoProducto = e => {
    e.preventDefault()
    setLoading(true)

    let id = null

    if (indexSelectInventario!=null) {
      if (productosInventario[indexSelectInventario]) {
        id = productosInventario[indexSelectInventario].id
      }
    }

    let id_factura = null

    if (factSelectIndex!=null) {
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

    }).then(res=>{
      notificar(res)
      buscarInventario()
      getFacturas(null)

      setLoading(false)

      if (res.data.estado) {

        setinpInvbarras("")
        setinpInvcantidad("")
        setinpInvalterno("")
        setinpInvunidad("UND")
        setinpInvcategoria("24")
        setinpInvdescripcion("")
        setinpInvbase("")
        setinpInvventa("")
        setinpInviva("")
        setinpInvid_marca("")
      }
    })
  }

  const delProducto = e => {
    let id;
    if (indexSelectInventario!=null) {
      if (productosInventario[indexSelectInventario]) {
        id = productosInventario[indexSelectInventario].id
      }
    }
    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delProducto({id}).then(res=>{
        setLoading(false)
        buscarInventario()
        notificar(res)
        if (res.data.estado) {
          setIndexSelectInventario(null)
        }
      })
      
    }
  }

  const setProveedor = e =>{
    setLoading(true)
    e.preventDefault()

    let id = null

    if (indexSelectProveedores!=null) {
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
    }).then(res=>{
      notificar(res)
      getProveedores()
      setLoading(false)

    })
  } 
  const delProveedor = e => {
    let id;
    if (indexSelectProveedores!=null) {
      if (proveedoresList[indexSelectProveedores]) {
        id = proveedoresList[indexSelectProveedores].id
      }
    }
    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delProveedor({id}).then(res=>{
        setLoading(false)
        getProveedores()
        notificar(res)

        if (res.data.estado) {
          setIndexSelectProveedores(null)
        }
      })

    }

  }

  const getProveedores = e => {
    setLoading(true)
    db.getProveedores({
      q:qBuscarProveedor
    }).then(res=>{
      setProveedoresList(res.data)
      setLoading(false)
      if (res.data.length===1) {
        setIndexSelectProveedores(0)
      }
    })

    

    db.getDepositos({
      q:qBuscarProveedor
    }).then(res=>{
      setdepositosList(res.data)
    })
  }

   const setFactura = e => {
    e.preventDefault()
    setLoading(true)

    let id = null

    if (factSelectIndex!=null) {
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
    }).then(res=>{
      notificar(res)
      getFacturas()
      setLoading(false)
      if (res.data.estado) {
        setfactsubView("buscar")
        setfactSelectIndex(null)
      }

    })
  }
  const getFacturas = (clean = true) =>{
    setLoading(true)
    db.getFacturas({
      factqBuscar,
      factqBuscarDate,
      factOrderBy,
      factOrderDescAsc
    }).then(res=>{
      setLoading(false)
      setfacturas(res.data)

      if (clean) {
        setfactSelectIndex(null)

      }
    })
  }

  const delFactura = e => {
    let id = null

    if (factSelectIndex!=null) {
      if (facturas[factSelectIndex]) {
        id = facturas[factSelectIndex].id
      }
    }
    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delFactura({id}).then(res=>{
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

  const delItemFact = e =>{
    let id = e.currentTarget.attributes["data-id"].value

    if (confirm("¿Desea Eliminar?")) {
      setLoading(true)
      db.delItemFact({id}).then(res=>{
        setLoading(false)
        notificar(res)
        if (res.data.estado) {
          getFacturas(false)
          buscarInventario()
        }
      })
    }
  }


  return(
    <>
      {msj!=""?<Notificacion msj={msj} notificar={notificar}/>:null}

      {loading?<Cargando active={loading}/>:null}

      <Header 
      setView={setView} 
      view={view}
      sucursalSelect={sucursalSelect}
      setsucursalSelect={setsucursalSelect}
      />
      <Toplabel sucursales={sucursales} sucursalSelect={sucursalSelect}/>
      <div className="container marginb-6 margint-6 p-0">
        {sucursalSelect===null?
          <SelectSucursal 
          setsucursalSelect={setsucursalSelect} 
          sucursalSelect={sucursalSelect}
          sucursales={sucursales}
          />
        :<>
          {view=="fallas"?<FallasComponent
            fallas={fallas}
          />:null}

          {view=="gastos"?<GastosComponent
            gastos={gastos}
            selectgastos={selectgastos}
            setselectgastos={setselectgastos}
            setfechaGastos={setfechaGastos}
            fechaGastos={fechaGastos}
            tipogasto={tipogasto}
            settipogasto={settipogasto}
            moneda={moneda}

          />:null}

          {view=="ventas"?<VentasComponent
            ventas={ventas}
            selectfechaventa={selectfechaventa}
            setselectfechaventa={setselectfechaventa}
            moneda={moneda}
          />:null}


  



          {view=="inventario"?<InventarioComponent
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

            moneda={moneda}

            subviewProveedores={subviewProveedores}
            setsubviewProveedores={setsubviewProveedores}

            subviewCargarProductos={subviewCargarProductos}
            setsubviewCargarProductos={setsubviewCargarProductos}
          />:null}

          
          
          
        </>}
      </div>

    </>
  );
}
render(<Home/>,document.getElementById('app'));

