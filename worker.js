async function handleQdApiRequest(event) {
  const url = new URL(event.request.url);
  const id = url.searchParams.get('id');
  if (!id) {
    console.log('No id parameter found in the URL.');
    return new Response('No id parameter found in the URL.', { status: 400 });
  }

  const apiUrl = `https://bd-api.kuwo.cn/api/ucenter/vip/give/popup?action=play&uid=${id}&token=137acd3e6d0276020741da2ef35a316b`;
  const headers = {
    'Host': 'bd-api.kuwo.cn',
    'Origin': 'https://h5app.kuwo.cn',
    'Connection': 'keep-alive',
    'plat': 'ip',
    'channel': 'appstore',
    'brand': 'iPhone13,1',
    'devid': '7A03C7BC-26F2-4482-9031-E14CFC11CF33',
    'ver': '3.2.3',
    'Accept': 'application/json, text/plain, */*',
    'User-Agent': 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 BoDianMusic',
    'Referer': 'https://h5app.kuwo.cn/',
    'Accept-Language': 'zh-CN,zh-Hans;q=0.9'
  };

  const response = await fetch(apiUrl, { headers });
  if (response.ok) {
    const data = await response.json();
    console.log(data);
    return new Response(JSON.stringify(data), { status: 200, headers: { 'Content-Type': 'application/json' } });
  } else {
    console.log('Request failed:', response.status);
    return new Response('Request failed', { status: response.status });
  }
}

async function handleUserApiRequest(event) {
  const url = new URL(event.request.url);
  const id = url.searchParams.get('id');
  if (!id) {
    console.log('No id parameter found in the URL.');
    return new Response('No id parameter found in the URL.', { status: 400 });
  }

  const apiUrl = `https://bd-api.kuwo.cn/api/ucenter/users/pub/${id}?fromUid=19374293&platform=ios`;
  const headers = {
    'Host': 'bd-api.kuwo.cn',
    'Origin': 'https://h5app.kuwo.cn',
    'Connection': 'keep-alive',
    'plat': 'ip',
    'channel': 'appstore',
    'brand': 'iPhone13,1',
    'devid': '7A03C7BC-26F2-4482-9031-E14CFC11CF33',
    'ver': '3.2.3',
    'Accept': 'application/json, text/plain, */*',
    'User-Agent': 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 BoDianMusic',
    'Referer': 'https://h5app.kuwo.cn/',
    'Accept-Language': 'zh-CN,zh-Hans;q=0.9'
  };

  const response = await fetch(apiUrl, { headers });
  if (response.ok) {
    const data = await response.json();
    console.log(data);
    return new Response(JSON.stringify(data), { status: 200, headers: { 'Content-Type': 'application/json' } });
  } else {
    console.log('Request failed:', response.status);
    return new Response('Request failed', { status: response.status });
  }
}

addEventListener('fetch', (event) => {
  const { pathname } = new URL(event.request.url);
  if (pathname === '/qdapi') {
    event.respondWith(handleQdApiRequest(event));
  } else if (pathname === '/userapi') {
    event.respondWith(handleUserApiRequest(event));
  }
});
