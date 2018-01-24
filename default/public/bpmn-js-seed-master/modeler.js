/**
 * bpmn-js-seed
 *
 * This is an example script that loads an embedded diagram file <diagramXML>
 * and opens it using the bpmn-js viewer.
 */

  var canvas;
  var overlays;
  var bpmnViewer;
(function(BpmnViewer) {

  // create viewer
  bpmnViewer = new BpmnViewer({
    container: '#canvas'
  });


  


  // a diagram to display
  //
  // see index-async.js on how to load the diagram asynchronously from a url.
  // (requires a running webserver)
// loadXMLDoc('../bpmn-js-seed-master/pizaa2.bpmn');

  
  

})(window.BpmnJS);


function loadXMLDoc(xml) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
       importXML(this.responseText);
      }
    };
    xmlhttp.open("GET", xml, true);
    xmlhttp.send();

  }

// import function
  function importXML(xml) {

    // import diagram
      bpmnViewer.importXML(xml, function(err) {

        if (err) {
          return console.error('could not import BPMN 2.0 diagram', err);
        }

        canvas = bpmnViewer.get('canvas');
        overlays = bpmnViewer.get('overlays');


        // zoom to fit full viewport
        canvas.zoom('fit-viewport');
        canvas.get('zoomScroll').stepZoom(1)
        /*dataOverlays  = [{"id":"_6-53","texto":"controlador_pizza_customer","bottom":-3,"right":0},{"id":"_6-650","texto":"perfil_clerk","bottom":-3,"right":150},{"id":"_6-446","texto":"perfil_pizza_chef","bottom":-3,"right":150},{"id":"_6-448","texto":"perfil_delivery_boy","bottom":-3,"right":150},{"id":"_6-438","texto":"controlador_pizza_vendor","bottom":-3,"right":0}];
        $.each( dataOverlays, function( key, val ) {
           console.log(val.id);
           overlaysEle(val.id, val.texto, val.right)
        });*/
        
      });
   
    




    // save diagram on button click
    var saveButton = document.querySelector('#save-button');

    saveButton.addEventListener('click', function() {

      // get the diagram contents
      bpmnViewer.saveXML({ format: true }, function(err, xml) {

        if (err) {
          console.error('diagram save failed', err);
        } else {
          alert('diagram saved');
           console.log(xml);
          guardaXml(xml);
        }

        //alert('diagram saved (see console (F12))');
      });
    });

    // dowload json overlays
    var saveOverlays = document.querySelector('#save-overlays');

    saveOverlays.addEventListener('click', function() {

      downloadObjectAsJson(student, 'exportName');


    });

  }



var student = [];
var obj = {};
function overlaysEle(idElemento, texto, right){

    if(right == undefined){
      right = 150;
    }else{
      right = right;
    }
    //bustar el overlays
    //var  overlaysR = bpmnViewer.get('overlays').get({ element: idElemento });
    //console.log(overlaysR[0].id);
    var  overlaysR = bpmnViewer.get('overlays');
    console.log(overlaysR);

    // eliminar el overlay del elemento
    overlays.remove({ element: idElemento });
    //creamos el overlays nuevo

    var html = '<div class="diagram-note">'+getCleanedString(texto)+'</div>';
    var overlayId = overlays.add(idElemento, 'note', {
      position: {
        bottom: -3,
        right: right,
      },
      html: html
    });
    console.log(overlayId);

    obj = {
        'id': idElemento,
        'texto': getCleanedString(texto),
        'bottom': -3,
        'right': right
    }
    student.push(obj);
    console.log(student);
    
    canvas.addMarker(idElemento, 'needs-discussion');

}


function downloadObjectAsJson(exportObj, exportName){
    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj));
    var downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href",     dataStr);
    downloadAnchorNode.setAttribute("download", exportName + ".json");
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
  }




function getCleanedString(cadena){
   // Definimos los caracteres que queremos eliminar
   var specialChars = "!@#$^&%*()+=-[]\/{}|:<>?,.";

   // Los eliminamos todos
   for (var i = 0; i < specialChars.length; i++) {
       cadena= cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
   }   

   // Lo queremos devolver limpio en minusculas
   cadena = cadena.toLowerCase();

   // Quitamos espacios y los sustituimos por _ porque nos gusta mas asi
   cadena = cadena.replace(/ /g,"_");

   // Quitamos acentos y "ñ". Fijate en que va sin comillas el primer parametro
   cadena = cadena.replace(/á/gi,"a");
   cadena = cadena.replace(/é/gi,"e");
   cadena = cadena.replace(/í/gi,"i");
   cadena = cadena.replace(/ó/gi,"o");
   cadena = cadena.replace(/ú/gi,"u");
   cadena = cadena.replace(/ñ/gi,"n");
   return cadena;
}