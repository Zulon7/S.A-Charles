function close_element(target) {
    if(typeof(target) != 'string') return;
    // Array.from copia a lista: getElementsByClassName é "viva" e encolhe
    // a cada remove(), o que fazia o for pular elementos
    const elements = Array.from(document.getElementsByClassName(target));

    for (const element of elements) {
        element.remove();
    }
}

