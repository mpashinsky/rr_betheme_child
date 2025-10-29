jQuery(window).on('load', function($){
    if(typeof jQuery.fn.isotope !== 'undefined' && typeof mfn_isotope === 'function'){
        mfn_isotope();
    } else {
        console.warn('Isotope is missing or mfn_isotope not defined');
    }
});
