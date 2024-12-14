export function sizeMap(name){
    let array = name.split('x');
    let string = '';
    for (let i=0; i < array.length; i++) {
        if ( Number(array[i]) === 512 ){
            string += '10'
        }
        else if (Number(array[i]) === 4096){
            string += '80'
        }
        else if (Number(array[i]) === 2048){
            string += '40'
        }
        else if (Number(array[i]) === 1024){
            string += '20'
        }
        else if (Number(array[i]) === 768){
            string += '15'
        }
        else if (Number(array[i]) === 640){
            string += '12.5'
        }
        else if (Number(array[i]) === 256){
            string += '5'
        }
        else if (Number(array[i]) === 128){
            string += '2'
        }
        else if (Number(array[i]) === 64){
            string += '1'
        }

        if ( i === 0 ){
            string += 'x'
        }
    }

    return string
}
