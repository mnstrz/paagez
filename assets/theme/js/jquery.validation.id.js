$.extend(jQuery.validator.messages, {
    required: "Bidang ini harus diisi.",
    remote: "Tolong perbaiki bidang ini.",
    email: "Silahkan masukkan alamat email yang valid.",
    url: "Silakan masukkan URL yang valid.",
    date: "Silakan masukkan tanggal yang valid.",
    dateISO: "Silakan masukkan tanggal yang valid (ISO).",
    number: "Silakan masukkan angka yang valid.",
    digits: "Silakan masukkan hanya angka.",
    creditcard: "Silakan masukkan nomor kartu kredit yang valid.",
    equalTo: "Silakan masukkan nilai yang sama lagi.",
    accept: "Silakan masukkan nilai dengan ekstensi yang valid.",
    maxlength: jQuery.validator.format("Silakan masukkan tidak lebih dari {0} karakter."),
    minlength: jQuery.validator.format("Silakan masukkan setidaknya {0} karakter."),
    rangelength: jQuery.validator.format("Silakan masukkan nilai antara {0} dan {1} karakter panjang."),
    range: jQuery.validator.format("Silakan masukkan nilai antara {0} dan {1}."),
    max: jQuery.validator.format("Silakan masukkan nilai kurang dari atau sama dengan {0}."),
    min: jQuery.validator.format("Silakan masukkan nilai yang lebih besar atau sama dengan {0}.")
});