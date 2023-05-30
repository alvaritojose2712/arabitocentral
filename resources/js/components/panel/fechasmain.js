export default function FechasMain({
    fechasMain1,
    fechasMain2,
    setfechasMain1,
    setfechasMain2,
}){

    return(
        <div className="input-group mb-3">
            <input type="date" className="form-control" value={fechasMain1} onChange={e=>setfechasMain1(e.target.value)}/>
            <input type="date" className="form-control" value={fechasMain2} onChange={e=>setfechasMain2(e.target.value)}/>

        </div>
    )
}
