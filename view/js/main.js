// Variables globales.
var arrJson = [[],[]];
var arrayProperties = [[],[]];

// Muestra y oculta el menú.
document.querySelector('#menu').addEventListener('click', () => {
    let menu = new WebComponent();
    // Alterna el valor de la variable css.
    let customProperty = menu.getCustomProperty('--displayMenu');
    if (customProperty === ' none' || customProperty === 'none') {
        menu.setCustomProperty('--displayMenu','block');
    } else {
        menu.setCustomProperty('--displayMenu','none');
    }
    const style = new CssStyle();
    // Creo una nueva hoja de estilos.
    style.styleSheet();
    // Elimino Reglas css.
    style.deleteCssRule();
    // Inserto una nueva regla css.
    style.insertCssRule();
});

// Escuchador para el boton que muestra listado de productos.
document.querySelector('.menu #getAllProducts').addEventListener('click', () => {
    let product = new Product();
    product.getAll();
});

class Product {
    // Método para obtener todos los productos.
    getAll() {
        fetch('http://localhost/MiniApiRest/controller/get_all.php')
        .then(response => {
            return response.json();
        })
        .then(response => {
            // Reinicio el array.
            arrJson.splice(1,arrJson.length);
            arrJson = [[],[]];

            let resp = new Response();

            /*
                Método recursivo:
                    -Recorro el Json obtenido de la respuesta del servidor.
                    -Inserta sus 'propiedades' (key) en la posición 0 del array "arrJson".
                        -Estas propiedades son la Metadata de la base de datos. (id,nombre,precio).
                    -Inserta los valores de las propiedades (values) en la posición 1 del array "arrJson".
                        - Estos valores son la informació de los productos cargados.
            */
            resp.dumpResponse(response);

            let table = new Table();

            // Si ya hay una tabla creada la elimina.
            table.clean();

            // Imprimo Metadata (id,nombre,precio).
            table.printMetadata();

            // Imprimo información de los productos cargados.
            table.printTbody();

            /*
                Muestro los botones de la tabla.
                Reemplazar esta parte del código:
                    -Crear un método en la clase 'Table' que haga lo siguiente:
                        -Mostrar número de páginas
                        -Mostrar cantidad de registros
                        -Habilitar botones (Solo si no se seleccionó 'Ver todos los productos')
            */
            let buttonsTable = new WebComponent();
            buttonsTable.setCustomProperty('--displayTfoot', 'table-row');
        });
    }
}

// Método recursivo.
class Response {
    dumpResponse(obj) {
        for (let property in obj) {
            let valueProperty = obj[property];
            if (typeof valueProperty !== "object") {
                if (property != 'type') {
                    arrJson[0].push(property);
                    arrJson[1].push(valueProperty);
                }
            }
            if (typeof valueProperty === "object") {
                this.dumpResponse(valueProperty);
            }
        }
    }
}

class Table {
    clean() {
        const tbody = document.querySelector('table > tbody');
        const thead = document.querySelector('table > thead > tr');
        if (tbody.hasChildNodes()) {
            while (tbody.hasChildNodes()) {
                tbody.removeChild(tbody.lastChild);
            }
        }
        if (thead.hasChildNodes()) {
            while (thead.hasChildNodes()) {
                thead.removeChild(thead.lastChild);
            }
        }
    }
    printMetadata() {
        const thead = document.querySelector('table > thead > tr');
        let metadata = [];

        // Elimino valores duplicados y lo asigno a un nuevo array.
        metadata = arrJson[0].filter((item, index, array) => {
            return array.indexOf(item) === index;
        });

        // Recorro array e imprimo sus valores.
        metadata.forEach(value => {
            if (value === 'precio') {
                value = value + ' en Pesos';
            }

            let str = new String();
            value = str.toUpperCamelCase(value);

            let th = document.createElement('th');
            let text = document.createTextNode(value);

            th.appendChild(text);
            thead.appendChild(th);
        });
    }
    printTbody() {
        const tbody = document.querySelector('table > tbody');
        const metadata = document.querySelectorAll('table > thead > tr > th');
        let tr, td, input, text;
        let i = 0;

        arrJson[1].forEach(value => {
            if (i === 0) {
                tr = document.createElement('tr');
            }

            if (i < metadata.length) {
                td = document.createElement('td');
                input = document.createElement('input');
                input.value = value;
                input.readOnly = true;
                //text = document.createTextNode(value);
                //td.appendChild(text);
                td.appendChild(input);
                tr.appendChild(td);
                tbody.appendChild(tr);
            }

            i++;

            if (i === 4) {
                tr = '';
                i = 0;
            }
        });
    }
}

// Clase que contiene un método para parsear un string en upper camel case.
class String {
    toUpperCamelCase(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
}

// Clase para obtener y cambiar propiedades css.
class WebComponent {
    getCustomProperty(customProperty) {
        return getComputedStyle(document.documentElement).getPropertyValue(customProperty);
    }
    
    setCustomProperty(customProperty,value) {
        let index = arrayProperties[0].indexOf(customProperty);
        if (index === -1) {
            arrayProperties[0].push(customProperty);
            arrayProperties[1].push(value);
        } else {
            arrayProperties[1][index] = value;
        }
    }
}

class CssStyle {
    constructor(styl = '') {
        this.style = styl;
    }
    
    get styl() {
        return this.style;
    }

    set styl(style) {
        this.styl = style;
    }
    
    // Obtengo una hoja de estilos, caso contrario la creo.
    styleSheet() {
        this.style = document.querySelector('head style');
        const head = document.querySelector('head');
        if (this.style === null) {
            this.style = document.createElement('style');
            head.appendChild(this.style);
        }
    }

    // Inserto una nueva regla en la hoja de estilos.
    insertCssRule() {
        const sheet = this.style.sheet ? this.style.sheet : this.style.styleSheet;
        let selector = ':root {';
        let rules ='';
        let ruleComplete;

        for (let i = 0; i < arrayProperties[0].length; i++) {
            let customProperty = arrayProperties[0][i];
            let value = arrayProperties[1][i];

            /*
            let rule = `:root {
                ${customProperty}: ${value};
            }`;
            */

            rules = rules + `${customProperty}: ${value};`;
        }
        rules = rules + '}'
        ruleComplete = selector + rules;
        sheet.insertRule(ruleComplete, 0);
    }

    // Elimino una regla de la hoja de estilos.
    deleteCssRule() {
        const sheet = this.style.sheet ? this.style.sheet : this.style.styleSheet;
        if (sheet.cssRules.length > 0) {
            for (let i = 0; i < sheet.cssRules.length; i++) {
                if (sheet.cssRules[i].selectorText === ':root') {
                    sheet.deleteRule(i);
                }
            }
        }
    }
}