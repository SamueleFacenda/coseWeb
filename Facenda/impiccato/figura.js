
function useCanvasHangman(){
    const draw = function(ctx, pathFromx, pathFromy, pathTox, pathToy) {            
        ctx.moveTo(pathFromx, pathFromy);
        ctx.lineTo(pathTox, pathToy);
        ctx.stroke(); 
    }

    let frame1 = function(ctx) {
        draw (ctx, 0, 150, 150, 150);
    };
    let frame2 = function(ctx) {
        draw (ctx, 10, 0, 10, 600);
    };
    let frame3 = function(ctx) {
        draw (ctx, 0, 5, 70, 5);
    };
    let frame4 = function(ctx) {
        draw (ctx, 60, 5, 60, 15);
    };
    let body = function(ctx) {
        draw (ctx, 60, 36, 60, 70);
    };
    let rightArm = function(ctx) {
        draw (ctx, 60, 46, 100, 50);
    };
    let leftArm = function(ctx) {
        draw (ctx, 60, 46, 20, 50);
    };
    let rightLeg = function(ctx) {
        draw (ctx, 60, 70, 100, 100);
    };
    let leftLeg = function(ctx) {
        draw (ctx, 60, 70, 20, 100);
    };
    const canvas = function(ctx){
        ctx.beginPath();
        ctx.clearRect(0, 0, 400, 400);
        ctx.strokeStyle = "#000";
        ctx.lineWidth = 2;
    };
    const canvasVinto = function(ctx){
        //disegno tutto l'omino
        for(let i = 0; i <=5; i++)
            drawArray[i](ctx);
        ctx.beginPath();
        ctx.arc(60, 25, 7, 0, Math.PI, false);
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(58, 22, 1, 0, Math.PI*2, true);
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(62, 22, 1, 0, Math.PI*2, true);
        ctx.stroke();
        
    };
    const canvasPerso = function(ctx){
        ctx.beginPath();
        ctx.arc(60, 32, 6, -0.5, -Math.PI+0.5, true);
        ctx.stroke();
        /*
        ctx.beginPath();
        ctx.arc(58, 22, 1, 0, Math.PI*2, true);
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(62, 22, 1, 0, Math.PI*2, true);
        ctx.stroke();
        */
        //occhi a croce
        draw(ctx, 55, 20, 59, 24);
        draw(ctx, 59, 20, 55, 24);

        draw(ctx, 65, 20, 61, 24);
        draw(ctx, 61, 20, 65, 24);
    };

    let head = function(ctx){
        ctx.beginPath();
        ctx.arc(60, 25, 10, 0, Math.PI*2, true);
        ctx.stroke();
    }
    const drawArray = [rightLeg, leftLeg, rightArm, leftArm,  body,  head, frame4, frame3, frame2, frame1, canvas];     
    const canvasRef = React.useRef(null);
    const [ vite, setVite ] = React.useState(null);
    const [ vinto, setVinto ] = React.useState(null);

    React.useEffect(() => {
        let lives = vite == null? drawArray.length - 1 : vite;
        const canvasObj = canvasRef.current;
        const ctx = canvasObj.getContext('2d');
        drawArray[lives](ctx);
    },[vite]);

    React.useEffect(() => {
        if(vinto != null){
            const canvasObj = canvasRef.current;
            const ctx = canvasObj.getContext('2d');
            if(vinto)
                canvasVinto(ctx);
            else
                canvasPerso(ctx);
        }
    },[vinto]);

    return [canvasRef, setVite, setVinto];


}

function Figura(props){
    const [ canvasRef, setVite, setVinto ] = useCanvasHangman();
    React.useEffect(() => {
        setVite(props.vite);
    },[props.vite]);
    React.useEffect(() => {
        setVinto(props.vinto);
    },[props.vinto]);
    return <div>
        <div className="figura">Vite rimaste: {props.vite}</div>
        <canvas ref={canvasRef}>
            <p>Il tuo browser non supporta il canvas</p>
        </canvas>
    </div>
}