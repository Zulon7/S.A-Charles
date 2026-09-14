function close_element(target) {
    if(typeof(target) != 'string') return;
    elements = document.getElementsByClassName(target);
    
    for (i = 0; i < elements.length; i++) {
        elements[i].remove();

    }
}

