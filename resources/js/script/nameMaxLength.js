export function nameMaxLength(name){
    if ( name.length > 26){
        let fix = name.match(/.{1,25}/g);
        let string ='';

        for (let i=0; i < fix.length; i++) {
            if (i > 0) {
                string += '<span style="display:none" class="fixed-name-file-el">' + fix[i] + '</span>';
            }
            else{
                string += '<span class="fixed-name-file-el">' + fix[i] + '<span class="hide-name">...</span></span>';
            }
        }

        return string
    }
    return name;
}
