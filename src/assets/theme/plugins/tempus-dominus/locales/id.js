/*!
  * Tempus Dominus v6.2.7 (https://getdatepicker.com/)
  * Copyright 2013-2022 Jonathan Peterson
  * Licensed under MIT (https://github.com/Eonasdan/tempus-dominus/blob/master/LICENSE)
  */
(function(g,f){typeof exports==='object'&&typeof module!=='undefined'?f(exports):typeof define==='function'&&define.amd?define(['exports'],f):(g=typeof globalThis!=='undefined'?globalThis:g||self,f((g.tempusDominus=g.tempusDominus||{},g.tempusDominus.locales=g.tempusDominus.locales||{},g.tempusDominus.locales.fr={})));})(this,(function(exports){'use strict';const name = 'fr';
const localization = {
    today: 'Hari Ini',
    clear: 'Bersihkan',
    close: 'Tutup',
    selectMonth: 'Pilih Bulan',
    previousMonth: 'Bulan Sebelumnya',
    nextMonth: 'Bulan Selanjutnya',
    selectYear: 'Pilih Tahun',
    previousYear: 'Tahun Sebelumnya',
    nextYear: 'Tahun Selanjutnya',
    selectDecade: 'Pilih Dekade',
    previousDecade: 'Dekade Sebelumnya',
    nextDecade: 'Dekade Selanjutnya',
    previousCentury: 'Abad Sebelumnya',
    nextCentury: 'Abad Selanjutnya',
    pickHour: 'Pilih Jam',
    incrementHour: 'Tambahkan Jam',
    decrementHour: 'Kurangi Jam',
    pickMinute: 'Pilih Menit',
    incrementMinute: 'Tambahkan Menit',
    decrementMinute: 'Kurangi Menit',
    pickSecond: 'Pilih Detik',
    incrementSecond: 'Tambahkan Detik',
    decrementSecond: 'Kurangi Detik',
    toggleMeridiem: 'Format AM-PM',
    selectTime: 'Pilih Waktu',
    selectDate: 'Pilih Tanggal',
    dayViewHeaderFormat: { month: 'lengkap', year: '2-digit' },
    locale: 'id',
    startOfTheWeek: 1,
    dateFormats: {
        LT: 'HH:mm',
        LTS: 'HH:mm:ss',
        L: 'DD/MM/YYYY',
        LL: 'D MMMM YYYY',
        LLL: 'D MMMM YYYY HH:mm',
        LLLL: 'dddd D MMMM YYYY HH:mm'
    },
    ordinal: (n) => {
        const o = n === 1 ? 'id' : '';
        return `${n}${o}`;
    },
    format: 'L LT'
};exports.localization=localization;exports.name=name;Object.defineProperty(exports,'__esModule',{value:true});}));