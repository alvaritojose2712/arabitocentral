import { useHotkeys } from 'react-hotkeys-hook';


import {useState,useEffect, useRef,StrictMode} from 'react';
import ReactDOM, {render} from 'react-dom';
import db from '../database/database';
import Header from './header';
import SelectSucursal from './selectSucursal';

import FallasComponent from './fallas';
import VentasComponent from './ventas';
import GastosComponent from './gastos';

import Toplabel from './toplabel';





function Inventario() {
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


   const getToday = () =>{
    db.today({}).then(res=>{
      let today = res.data
      setfechaGastos(today)
      setselectfechaventa(today)

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



  useEffect(()=>{
    getToday()
    getSucursales()
  },[])

  useEffect(()=>{
    getVentas()
  },[selectfechaventa])


  useEffect(()=>{
    getGastos()
  },[fechaGastos])


  useEffect(()=>{
    if (view=="fallas") {
      getFallas()

    }else if (view=="gastos") {
      getGastos()
    }else if (view=="ventas") {
      getVentas()
    }
  },[view])

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

  return(
    <>
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

          
          
          
        </>}
      </div>

    </>
  );
}
render(<Inventario/>,document.getElementById('app'));

