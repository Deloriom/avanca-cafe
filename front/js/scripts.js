let proprietario = '';
let tipoCalculo = '';
let talhao = '';
let saturacaoIdeal = '';
let saturacaoSolo = '';
let ctc = '';
let calcio = '';
let aluminio = '';
let magnesio = '';
let teorArgila = '';
let teorMaximo = '';
let btnConfirma = '';
let liCalculo = '';
let loading = '';
let divSatBase = '';
let divTeorAl = '';
let divCalMag = '';
let divResult = '';

window.addEventListener('load', () => {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems, {});

    loadFields();
    btnConfirma.addEventListener('click', () => { realizaCalculo(event); })
    tipoCalculo.addEventListener('change', () => { controleFields(event); })
})

function loadFields() {
    proprietario = document.querySelector('#nome-proprietario');
    tipoCalculo = document.querySelector('#tipoCalculo');
    talhao = document.querySelector('#talhao');
    saturacaoIdeal = document.querySelector('#saturacao-ideal');
    saturacaoSolo = document.querySelector('#saturacao-solo');
    ctc = document.querySelector('#ctc');
    magnesio = document.querySelector('#magnesio');
    calcio = document.querySelector('#calcio');
    aluminio = document.querySelector('#aluminio');
    teorArgila = document.querySelector('#teor-argila');
    teorMaximo = document.querySelector('#teor-aluminio');
    btnConfirma = document.querySelector('#btn-confirma');
    loading = document.querySelector('#loading');
    divSatBase = document.querySelector('#div-sat-base');
    divTeorAl = document.querySelector('#div-teor-al');
    divCalMag = document.querySelector('#div-cal-mag');
    divResult = document.querySelector('#div-result');
}

function controleFields(event) {
    const opt = event.target.value;
    removeValidates([proprietario, talhao, saturacaoSolo, saturacaoIdeal, ctc, magnesio, calcio, aluminio, teorArgila, teorMaximo]);

    if (opt == 1) {
        teorArgila.parentNode.classList.add('none');
        teorMaximo.parentNode.classList.add('none');
        calcio.parentNode.classList.add('none');
        aluminio.parentNode.classList.add('none');
        magnesio.parentNode.classList.add('none');

        saturacaoIdeal.parentNode.classList.remove('none');
        saturacaoSolo.parentNode.classList.remove('none');

        divSatBase.classList.remove('none');
        divTeorAl.classList.add('none');
        divCalMag.classList.add('none');

    }
    if (opt == 2) {
        saturacaoIdeal.parentNode.classList.add('none');
        saturacaoSolo.parentNode.classList.add('none');

        teorArgila.parentNode.classList.remove('none');
        teorMaximo.parentNode.classList.remove('none');
        calcio.parentNode.classList.remove('none');
        aluminio.parentNode.classList.remove('none');
        magnesio.parentNode.classList.remove('none');

        divSatBase.classList.add('none');
        divTeorAl.classList.remove('none');
        divCalMag.classList.add('none');
    }
    if (opt == 3) {
        saturacaoIdeal.parentNode.classList.add('none');
        saturacaoSolo.parentNode.classList.add('none');
        teorArgila.parentNode.classList.add('none');
        teorMaximo.parentNode.classList.add('none');
        aluminio.parentNode.classList.add('none');

        calcio.parentNode.classList.remove('none');
        magnesio.parentNode.classList.remove('none');

        divSatBase.classList.add('none');
        divTeorAl.classList.add('none');
        divCalMag.classList.remove('none');
    }
}


function realizaCalculo(event) {
    event.preventDefault();
    if (tipoCalculo.value == 1) {
        if (validateFields([proprietario, talhao, saturacaoSolo, saturacaoIdeal, ctc])) {
            return;
        }
    }

    if (tipoCalculo.value == 2) {
        if (validateFields([proprietario, talhao, ctc, magnesio, calcio, aluminio, teorArgila, teorMaximo])) {
            return;
        }
    }

    if (tipoCalculo.value == 3) {
        if (validateFields([proprietario, talhao, ctc, magnesio, calcio])) {
            return;
        }
    }

    const dados = {
        "tipo_calculo": tipoCalculo.value,
        "nome_proprietario": proprietario.value,
        "area_ha": talhao.value,
        "saturacao_ideal": saturacaoIdeal.value,
        "saturacao_solo": saturacaoSolo.value,
        "ctc": ctc.value,
        "magnesio": magnesio.value,
        "calcio": calcio.value,
        "aluminio": aluminio.value,
        "teor_argila": 64,
        "teor_maximo_saturacao_aluminio": 5,
    }

    loading.classList.remove('none')
    btnConfirma.disabled = true;
    setTimeout(() => {
        reqApi(dados)
    }, 1000);
}

function removeValidates(fields) {
    fields.forEach(field => {
        field.classList.remove("invalid");
    })
}

function validateFields(fields) {
    let erro = false;
    fields.forEach(field => {
        if (field.value == '') {
            erro = true;
            field.classList.add("invalid");
        }
    })

    if (erro) {
        return true;
    }
}

function reqApi(dados) {
    const api = new XMLHttpRequest();
    api.open("POST", "http://localhost:8000/api/analise", true);
    api.setRequestHeader("Content-type", "application/json");
    api.send(JSON.stringify(dados));
    api.onreadystatechange = function () {
        if (api.readyState == 4 && api.status == 200) {
            var data = api.responseText;
            loading.classList.add('none')
            btnConfirma.disabled = false;
            reqTrue()
        } else {
            loading.classList.add('none')
            btnConfirma.disabled = false;
        }
    }
}


function reqTrue() {
    divResult.classList.remove('none');
    window.scrollTo({ top: divResult.getBoundingClientRect().top, behavior: 'smooth' })
    console.log(divResult.getBoundingClientRect().top)
}

