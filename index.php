<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日数カウントダウンAPI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function copyToClipboard() {
            const url = document.getElementById("request-url");
            url.select();
            document.execCommand("copy");
        }
        
        function updateUrl() {
            const futureDate = document.getElementById("future_date").value;
            const timezone = document.getElementById("timezone").value;
            const repeatAnnually = document.getElementById("repeat_annually").checked ? "true" : "false";
            const requestUrl = `https://●●Your Server●●/remaining_days/api?future_date=${futureDate}&timezone=${timezone}&repeat_annually=${repeatAnnually}`;
            document.getElementById("request-url").value = requestUrl;
        }
    </script>
</head>
<body class="">
    <div class="container mx-auto px-4 py-12">
        <h1 class="sr-only">日数カウントダウンAPI</h1>
        <form onsubmit="event.preventDefault(); updateUrl();" class="bg-white p-6 rounded-lg">
            <div class="mb-4">
                <label for="future_date" class="block text-gray-700 mb-2">目標日</label>
                <input type="date" name="future_date" id="future_date" required class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
                <label for="timezone" class="block text-gray-700 mb-2">タイムゾーン</label>
                <select name="timezone" id="timezone" required class="w-full p-2 border border-gray-300 rounded">
                    <?php foreach(timezone_identifiers_list() as $timezone): ?>
                        <option value="<?php echo $timezone; ?>" <?php echo $timezone === 'Asia/Tokyo' ? 'selected' : ''; ?>><?php echo $timezone; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" name="repeat_annually" id="repeat_annually" class="mr-2">
                <label for="repeat_annually" class="text-gray-700">毎年くりかえす</label>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">URLを生成</button>
        </form>

        <div class="mt-6">
            <label for="request-url" class="block text-gray-700 mb-2">APIのURL</label>
            <div class="flex">
                <input type="text" id="request-url" readonly class="w-full p-2 border border-gray-300 rounded mr-2">
                <button type="button" onclick="copyToClipboard()" class="whitespace-nowrap bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">URLをコピー</button>
            </div>
        </div>
    </div>
</body>
</html>
