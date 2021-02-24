declare const source: string;

import Hls from 'hls.js';
import Plyr from 'plyr';

document.addEventListener('DOMContentLoaded', () => {
  const video = document.getElementById('player') as HTMLVideoElement;

  new Plyr(video);

  if (!Hls.isSupported()) {
    video.src = source;
  } else {
    const hls = new Hls();
    hls.loadSource(source);
    hls.attachMedia(video);
  }
});
