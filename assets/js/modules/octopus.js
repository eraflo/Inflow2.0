export class Octopus {

    constructor(
        head,
        tentacles,
        container,
        canvas,
        //movingAlgo,
        //collisionResponse,
        directions = [1, 1]
    ) {
        this.head = head;
        this.tentacles = tentacles;
        this.container = container;
        this.canvas = canvas;
        //this.movingAlgo = movingAlgo;
        //this.collisionResponse = collisionResponse;
        this.directions = directions;
    }

    ConnectElements(elem1, elem2, canvas, ctx = null) {
        if (!ctx) {
            ctx = canvas.getContext('2d');
        }

        const canvasRect = canvas.getBoundingClientRect();
        
        // Get bounding boxes of elements
        const rect1 = elem1.getBoundingClientRect();
        const rect2 = elem2.getBoundingClientRect();
        
        // Calculate the start and end points of the line
        const startX = rect1.left - canvasRect.left + rect1.width / 2;
        const startY = rect1.top - canvasRect.top + rect1.height / 2;
        const endX = rect2.left - canvasRect.left + rect2.width / 2;
        const endY = rect2.top - canvasRect.top + rect2.height / 2;
        
        // Draw the line
        ctx.beginPath();
        ctx.moveTo(startX, startY);
        ctx.lineTo(endX, endY);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 3;
        ctx.stroke();
    }

    Init() {
        this.canvas.width = this.canvas.offsetWidth;
        this.canvas.height = this.canvas.offsetHeight;
        const ctx = this.canvas.getContext('2d');
        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        this.head.style.top = '50%';
        this.head.style.left = '50%';
        this.head.style.transform = 'translate(-50%, -50%)';

        const circle = this.head.querySelector('svg > circle');


        /* let angle = 300;
        // From https://codepen.io/mjurczyk/pen/wvBKOvP
        const radius = 50;
        const circumference = 2 * Math.PI * radius;
        const strokeOffset = 0;
        const strokeDasharray = (angle / 360) * circumference;
        circle.setAttribute('r', 50);
        circle.setAttribute('stroke-dasharray', [
            strokeDasharray,
            circumference - strokeDasharray
        ]);
        circle.setAttribute('stroke-dashoffset', strokeOffset); */

        let containerRect = this.container.getBoundingClientRect();

        for (let tentacle of this.tentacles) {

            tentacle.style.top = 0;
            tentacle.style.left = 0;
            tentacle.style.top = '50%';
            tentacle.style.left = '50%';
            tentacle.style.transform = 'translate(-50%, -50%)';

            let tentacleRect = tentacle.getBoundingClientRect();

            let randomSide = Math.random() * 2 | 0; // randomly 0 or 1

            // theta or pi - theta
            // Math.asin(this.container.offsetHeight / this.container.offsetWidth) = the min angle <=> sin(theta) = this.container.offsetHeight / this.container.offsetWidth
            // (min angle + 0..2 * min angle) => (- min angle)..(min angle)
            let randomAngle = - Math.asin(this.container.offsetHeight / this.container.offsetWidth) + 2 * Math.random() * Math.asin(this.container.offsetHeight / this.container.offsetWidth) + Math.PI * randomSide;
            //randomAngle = 2 * Math.random() * Math.PI;
            const tentacleLength = (this.container.offsetWidth - tentacle.offsetWidth - this.head.offsetWidth) / 2 * Math.random() + (tentacle.offsetWidth + this.head.offsetWidth) / 2;
            //console.log("tentacleLength: " + tentacleLength);
            //console.log("tentacleLength: " + tentacleLength + "; (tentacle.offsetWidth + this.head.offsetWidth) / 2: " + (tentacle.offsetWidth + this.head.offsetWidth) / 2);

            //console.log("x: " + (tentacleRect.left - containerRect.left + tentacleRect.width / 2) + ", y: " + (tentacleRect.top - containerRect.top + tentacleRect.height / 2));

            //let translatedBottom = ((containerRect.top + containerRect.height) - (tentacleRect.top + tentacleRect.height) + tentacleRect.height / 2) + tentacleLength * Math.sin(randomAngle);
            let translatedTop = tentacleLength * Math.sin(randomAngle);
            tentacle.style.top = `${translatedTop}px`;

            let translatedLeft = (tentacleRect.left - containerRect.left + tentacleRect.width / 2) + tentacleLength * Math.cos(randomAngle);
            tentacle.style.left = `${translatedLeft}px`;

            //console.log("calulatedTentacleLength: " + Math.sqrt(Math.pow(tentacleLength * Math.sin(randomAngle), 2) + Math.pow(tentacleLength * Math.cos(randomAngle), 2)));

            console.log("translatedLeft: " + translatedLeft);
            console.log("translatedTop: " + translatedTop);
            
            this.ConnectElements(tentacle, this.head, this.canvas, ctx);
        }
    }
    
    IsChildOutsideParent(parent, child) {
        const parentRect = parent.getBoundingClientRect();
        const childRect = child.getBoundingClientRect();
    
        const relativeChildLocation = [];
    
        // relativeChildLocation[0] = -1 => left || 1 => right
        // relativeChildLocation[1] = -1 => bottom || 1 => top
    
        relativeChildLocation[0] =
            (childRect.top < parentRect.top) ? 1 :
            (childRect.bottom > parentRect.bottom) ? -1 :
            0;
        
        relativeChildLocation[1] =
            (childRect.left < parentRect.left) ? -1 :
            (childRect.right > parentRect.right) ? 1 :
            0;
    
        return relativeChildLocation;
    }
    
    Animate(time) {

        // dtt = dist tentacle
        // 2*pi*dtt
        // dt = movingAlgo.Move(time)

        const relativeChildLocation = IsChildOutsideParent(parent, child);

        for (const key in relativeChildLocation) {

            if (relativeChildLocation[key] === 0) {
                continue;
            }

            this.directions[key] *= -1;

            //[xDisplacement, yDisplacement, multiplier] = collisionResponse.Compute(relativeChildLocation);
        }

        for (tentacle of this.tentacles) {
            const theta = movingAlgo.AngularDisplacement(time);
            tentacle.element.x = tentacle.length * Math.sin(theta) * this.directions[0];
            tentacle.element.y = tentacle.length * Math.cos(theta) * this.directions[1];
        }
    }
}