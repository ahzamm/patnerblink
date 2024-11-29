var originalConfig =     {
    "type":"ring",
    "title":{
        "text":""
    },
    "plot":{
        "detach":false,
        "cursor":"hande",
        "shadow":8,
        "tooltip":{
            "visible":false
        },
        "animation":{
            "delay":10,
            "effect":"2",
            "speed":"ANIMATION_FAST",
            "method":"1",
            "sequence":"3",
            "attributes":{

            }
        },
        "value-box":{
            "color":"#FFF",
            "text":"%t",
            "font-weight":"none",
            "font-size":14
        }
    },
    "series":[
    {
        "values":[20],
        "background-color":"#b6d135",
        "text":"Lite<br>Pack",
        "data-id":"vt"
    },
    {
        "values":[20],
        "background-color":"#35a849",
        "text":"Social <br>Pack",
        "data-id":"sp"
    },
    {
        "values":[20],
        "background-color":"#069d7e",
        "text":"Smart<br>Pack",
        "data-id":"sp"
    },
    {
        "values":[20],
        "background-color":"#095ba7",
        "text":"Power <br>Pack",
        "data-id":"sp"
    },
    {
        "values":[20],
        "background-color":"#3e2f84",
        "text":"Super<br>Pack",
        "data-id":"sp"
    },
    {
        "values":[20],
        "background-color":"#5e2c83",
        "text":"Turbo <br>Pack",
        "data-id":"sp"
    },

    {
        "values":[20],
        "background-color":"#cb202d",
        "text":"Mega <br>Pack",
        "data-id":"sp"
    }
    ,

    {
        "values":[20],
        "background-color":"#e54b27",
        "text":"Jumbo<br>Pack",
        "data-id":"sp"
    }
     ,

    {
        "values":[20],
        "background-color":"#ed7125",
        "text":"Super<br>Pack",
        "data-id":"sp"
    }

    ]
}

zingchart.render({
    id : 'profileGauge',
    data : originalConfig,
    height: 350,
    width: '80%'
});



/************************
*  Secondary Charts
* *********************/
var drilldownConfig = {
    "type":"bar",
    "title":{
        "text":"Security Tools"
    },
    "plotarea": {
        "margin":"dynamic"
    },
    "plot":{
        "animation":{
            "delay":10,
            "effect":"4",
            "speed":"1200",
            "method":"1",
            "sequence":"3"
        },
        "tooltip":{
            "text": "Quantity: %v",
            "shadow":true,
            "shadowAlpha":.5,
            "shadowBlur":2,
            "shadowDistance":3,
            "shadowColor":"#c4c4c4",
            "borderWidth":0,
            "font-size":18
        }
    },
    "series":[
    {
        "values":[35,15,25,10],
        "styles":["#1565C0","#42A5F5","#1E88E5","#90CAF9"]
    }
    ],
    "scale-x":{
        "line-color":"#555",
        "tick":{
            "line-color":"#555"
        },
        "values":["Firewall","Cache-control","Link-access","HTTP-Comp"],
        "item":{
            "max-chars":9,
            "color":"#555",
            "font-size":12
        },
        "label":{
            "text":"Type",
            "color":"#555",
            "font-weight":"none",
            "font-size":16
        }
    },
    "scale-y":{
        "line-color":"#555",
        "tick":{
            "line-color":"#555"
        },
        "item":{
            "color":"#555",
            "font-size":12
        },
        "guide":{
            "visible":false
        },
        "label":{
            "text":"Quantity",
            "color":"#555",
            "font-weight":"none",
            "font-size":16
        }
    },
    "shapes":[
    {
        'x':20,
        'y':20,
        'size':10,
        'angle':-90,
        'type':'triangle',
        'background-color':'#C4C4C4',
        'padding':5,
        'cursor':'hand',
        'id': 'backwards'
    }
    ]
};

/**
* Create associative array to manage drilldown config
*/
var drilldownDataStructure = [];
drilldownDataStructure["vt"] = {
    "data":[10,25,35],
    "scale-labels":["Grid-component","Map-tool","Web-charting"],
    "title":"Visualization Tools",
    "colors":["#EF5350","#E53935","#C62828"]
};
drilldownDataStructure["sp"] = {
    "data":[15,5,35,20],
    "scale-labels":["Speed-test","Error-tracking","Load-testing","User-monitoring"],
    "title":"Site Performance",
    "colors":["#26A69A","#80CBC4","#00695C","#00897B"]
};
drilldownDataStructure["dt"] = {
    "data":[20,8,35,20],
    "scale-labels":["IDE","File-Management","Image-Generation","QA-testing"],
    "title":"Dev Tools",
    "colors":['#26C6DA','#80DEEA','#00838F','#00ACC1']
};
drilldownDataStructure["st"] = {
    "data":[35,15,25,10],
    "scale-labels":["Firewall","Cache-control","Link-access","HTTP-Comp"],
    "title":"Security Tools",
    "colors":["#1565C0","#42A5F5","#1E88E5","#90CAF9"]
};
drilldownDataStructure["dm"] = {
    "data":[10,25,35],
    "scale-labels":["Relational","Non-relational","Cluster"],
    "title":"Data Management",
    "colors":["#5E35B1","#4527A0","#7E57C2"]
};