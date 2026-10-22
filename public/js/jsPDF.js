var doc = new jsPDF("l", "mm", "a4");

doc.text(17, 20, "Meu PDF");

// gerar a string
var base64String = doc.output('datauristring');