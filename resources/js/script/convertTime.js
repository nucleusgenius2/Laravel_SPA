export function convertTime(e){
    //конвертирует время из базы ларавел под часовой пояс юзера
    let timeZonaUsser = Intl.DateTimeFormat().resolvedOptions().timeZone
    return new Date(e).toLocaleString("ru-RU", {timeZone: timeZonaUsser })
}
