import * as THREE from 'three';
//import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import microModel from '../models/micro.glb';

function degToRad(degrees) {
  return degrees * Math.PI / 180;
}

let rendererWidthSize = window.innerWidth / 2;
let rendererHeightSize = window.innerHeight / 2;

const scene = new THREE.Scene();
scene.background = new THREE.Color(0xffffff);
const renderer = new THREE.WebGLRenderer();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
let mic;
let teta0;

//const controls = new OrbitControls(camera, renderer.domElement);
const loader = new GLTFLoader();

renderer.setSize(rendererWidthSize, rendererHeightSize);
document.body.querySelector('div.render').appendChild(renderer.domElement);

loader.load(microModel, function (gltf) {

  scene.add(gltf.scene);
  mic = gltf.scene;
  teta0 = - 3 * Math.PI / 6;
  mic.rotation.z = teta0;
  intersectedObjects.push(mic);

  animate();


}, undefined, function (error) {

  console.error(error);

});

const light = new THREE.AmbientLight(0xffffff, 2); // soft white light
scene.add(light);



camera.position.x = -10;
camera.position.y = -9;
camera.position.z = 11;

camera.rotation.x = degToRad(40);
camera.rotation.y = degToRad(-37);
camera.rotation.z = degToRad(26);
let t = 0;
let coef = 1;

// Create a raycaster
const raycaster = new THREE.Raycaster();

// Create a mouse vector
const mouse = new THREE.Vector2();

// Create an array to store intersected objects
const intersectedObjects = [];

let timeValues = [];

// Add a mousemove event listener to the document
document.addEventListener('mousemove', onMouseMove);

function onMouseMove(event) {
  // Calculate normalized mouse coordinates (-1 to 1) based on window size
  let rect = renderer.domElement.getBoundingClientRect();
  mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
  mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;


  // Update the raycaster with the mouse position
  raycaster.setFromCamera(mouse, camera);

  // Perform the raycasting
  const intersects = raycaster.intersectObjects(intersectedObjects);
  // Check if the ray intersects with any objects
  if (intersects.length > 0 && coef < 1 && ((timeValues[timeValues.length - 1] < (t - 2)) || timeValues.length === 0)) {
    timeValues.push(t);
  }
}

window.addEventListener('resize', onWindowResize, false)
function onWindowResize() {

  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(rendererWidthSize, rendererHeightSize);
  renderer.render(scene, camera);
}

let ZMicRotation;
let valuesToEvict = [];

function animate() {
  // controls.update();
  t += 1 / 60;
  coef = (coef > 0) ? Math.exp(-1 / 10 * t) * (1 + 1 / 10 * coef) : 0;

  /* for (let timeValue of timeValues) {
    let add = (Math.exp(-1/(4*(t - timeValue)))+Math.log((-(t - timeValue)+20)/20));
    if (add <= -0.01) {
      valuesToEvict.push(timeValue);
    } else {
      coef += add;
    }
  } */

  let add;

  for (let timeValue of timeValues) {
    if (t - timeValue >= 10) {
      add = (Math.exp(-1/(4*(t - timeValue)))+Math.log((-(t - timeValue)+20)/20));
    } else {
      add = (Math.exp(-1/(4*(t - timeValue)))+Math.log((-(t - timeValue)+20)/20));
    }
    coef += add;
  }

  /* for (let valueToEvict of valuesToEvict) {
    timeValues.splice(timeValues.indexOf(valueToEvict), 1);
  }
  valuesToEvict = []; */
  ZMicRotation = - teta0 * Math.sin(Math.sqrt(9.81 / 2) * t) * (coef) + Math.PI;
  mic.rotation.z = ZMicRotation;
  mic.position.x = - 4 * Math.sin(mic.rotation.z);
  mic.position.y = 4 * Math.cos(mic.rotation.z);
  
  renderer.render(scene, camera);
  requestAnimationFrame(animate);
}