function close(target) {
    console.log("entrou");
    if (typeof target != "string") return;
    console.log("passou");
    elements = getElementsByClassName(target);

    for (i = 0; i < length(elements); i++) {
        elements[i].remove();
        console.log("aaaaaaaaaaaa");
    }
}