import noise from 'perlin-noise';
import { Octopus } from './modules/octopus.js';
//import Lettering from './modules/lettering.js';

console.log("i'm sure it isn't");

function BuildOctopus() {

    const footer = document.querySelector("body > footer");
    const socials = footer.querySelector("#socials");

    const octopus = new Octopus(
        socials.querySelector(".head"),
        socials.querySelectorAll(".tentacle"),
        socials.querySelector(":scope > div"),
        socials.querySelector(":scope > div > canvas")
    );

    octopus.Init();

    /* for (let tentacle of socials.querySelectorAll(".tentacle")) {
        Lettering(tentacle.querySelector("p"));
    } */

    class MovingAlgo {

        constructor(
            time,
            timeStep
        ) {
            this.time = time;
            this.timeStep = timeStep;
        }

        Move(time) {
            // Generate noise values for X and Y positions
            const xDisplacement = noise.simplex2(time, 0) * 50; // Scale noise output
            const yDisplacement = noise.simplex2(0, time) * 50; // Scale noise output

            return [xDisplacement, yDisplacement];
        }

        AngularDisplacement(time) {
            // Generate noise values for X and Y positions
            const displacement = noise.simplex2(time, 0) * 50; // Scale noise output

            return displacement;
        }
    }



    function MoveChildInsideParent(child, relativeChildLocation, step) {

        child.style.top += relativeChildLocation[0] * step;
        child.style.right += relativeChildLocation[1] * step;
    }

    /* const intervalId = setInterval(moveChildInsideParent, intervalTime, child, isChildOutsideParent(parent, child), step);
        
    if (isChildOutsideParent(parent, child) == [0, 0]) {
        clearInterval(intervalId);
    } */

}

document.addEventListener('DOMContentLoaded', BuildOctopus);
window.addEventListener('resize', BuildOctopus);