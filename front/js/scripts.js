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
    calcio = document.querySelector('#calcio');
    aluminio = document.querySelector('#aluminio');
    teorArgila = document.querySelector('#teor-argila');
    teorMaximo = document.querySelector('#teor-aluminio');
    btnConfirma = document.querySelector('#btn-confirma');
    loading = document.querySelector('#loading');
    divSatBase = document.querySelector('#div-sat-base');
    divTeorAl = document.querySelector('#div-teor-al');
    divCalMag = document.querySelector('#div-cal-mag');
}

function controleFields(event) {
    const opt = event.target.value;

    console.log(opt)
    if (opt == 1) {
        teorArgila.parentNode.classList.add('none');
        teorMaximo.parentNode.classList.add('none');
        divSatBase.classList.remove('none');
        divTeorAl.classList.add('none');
        divCalMag.classList.add('none');
    }
    if (opt == 2) {
        teorArgila.parentNode.classList.remove('none');
        teorMaximo.parentNode.classList.remove('none');
        divSatBase.classList.add('none');
        divTeorAl.classList.remove('none');
        divCalMag.classList.add('none');
    }
    if (opt == 3) {
        teorArgila.parentNode.classList.add('none');
        teorMaximo.parentNode.classList.add('none');
        divSatBase.classList.add('none');
        divTeorAl.classList.add('none');
        divCalMag.classList.remove('none');
    }
}




function realizaCalculo(event) {
    event.preventDefault();

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
        console.log(dados)
    }, 1000);
    reqApi(dados)
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
        } else {
            loading.classList.add('none')
            btnConfirma.disabled = false;
        }
    }
}
