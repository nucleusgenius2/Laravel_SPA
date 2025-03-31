export function convertTime(e: string ): string{
    //конвертирует время из базы ларавел под часовой пояс юзера
    let timeZonaUsser : string = Intl.DateTimeFormat().resolvedOptions().timeZone

    return new Date(e).toLocaleString("ru-RU", {timeZone: timeZonaUsser })
}
