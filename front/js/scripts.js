let proprietario = '';
let tipoCalculo = '';
let talhao = '';
let saturacaoIdeal = '';
let saturacaoSolo = '';
let ctc = '';
let calcio = '';
let aluminio = '';
let magnesio = '';
let btnConfirma = '';


window.addEventListener('load', () => {
    loadFields();
    tipoCalculo.addEventListener('change', () => { controleFields(event); })
    btnConfirma.addEventListener('click', () => { realizaCalculo(event); })
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
    btnConfirma = document.querySelector('#btn-confirma');
}

function controleFields(event) {
    console.log(event)
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

    // Exemplo de requisição POST
    const api = new XMLHttpRequest();

    // Seta tipo de requisição: Post e a URL da API
    api.open("POST", "http://localhost:8000/api/analise", true);

    api.setRequestHeader("Content-type", "application/json");

    // Seta paramêtros da requisição e envia a requisição
    api.send(JSON.stringify(dados));

    // Cria um evento para receber o retorno.
    api.onreadystatechange = function () {

        // Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
        if (api.readyState == 4 && api.status == 200) {
            var data = api.responseText;

            // Retorno do api
            console.log(data);
        }
    }

}

