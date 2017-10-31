function ListarGridInstrumentos(id)
{
    return BaseX.Grid(id, {
        data: [],
        datatype: "local",
        colModel: [
            {name: 'a', label: 'Id', index: 'a', width: 10, align: "left", sortable: true, hidden: true, key: true},
            {name: 'b', label: 'Nombre de instrumento', index: 'b', width: 350, align: "left", sortable: true},
            {name: 't', label: 'Meta', index: 'm', width: 90, sortable: false},
            {name: 'i', label: 'Informantes', index: 'i', width: 250, sortable: true},
            {name: 'n', label: 'Estado', index: 'n', width: 90, align: "center", formatter: estadoINS, sortable: false},
            {name: 'a', label: 'Acciones', index: 'a', width: 200, formatter: config_btn_acciones, align: "center", sortable: false}
        ],
        rownumbers: false,
        width: 925,
        multiselect: false,
        shrinkToFit: false,
        hidegrid: false,
        pager: '#pager_listar_grid_instrumentos',
        resizable: true
    });
}
;

function GridInstrumentosDataConfig(obj) {
    BaseX.Grid('#listar_grid_instrumentos').clear();
    var listas_data = instrumentos_listar_all({});
    BaseX.Grid('#listar_grid_instrumentos').setData(listas_data);
}

function config_btn_acciones(cellvalue, options, rowObject) {
    var objeto = {
        id: rowObject['a'],
        t: rowObject['t']
    };
    var str = JSON.stringify(objeto);
    var btn_acciones = "";
    btn_acciones += "<button type='button' class='btn btn_primary_icons btn-md' data-toggle='tooltip' data-placement='top' title='Info. del instrumento' onclick='fn_edit_instrumento(" + str + ")'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></button>";
    
    return btn_acciones;
}

