function Board()
{
    return(
    <div id="terrain">
        <div id="terrain-joueur2">
            <div id="terrain-cadrillage-deck2">
                <span>deck</span>
                <div id="terrain-cadrillage-deck-contour2">
                    <div className="carte2">
                        <div className="carte-contour-verso"></div>
                    </div>
                </div>
            </div>
            <div id="terrain-cadrillage-duel2">
                <span>Duel</span>
                <div id="terrain-cadrillage-duel-contour2">
                </div>
            </div>
            <div id="terrain-cadrillage-cimetiere2">
                <span>Cimetière</span>
                <div id="terrain-cadrillage-cimetiere-contour2">
                    
                </div>
            </div>
        </div>
        <div id="terrain-joueur1">
            <div id="terrain-cadrillage-deck">
                <span>deck</span>
                <div id="terrain-cadrillage-deck-contour">
                    <div className="carte">
                        <div className="carte-contour-verso"></div>
                    </div>
                </div>
            </div>
            <div id="terrain-cadrillage-duel">
                <span>Duel</span>
                <div id="terrain-cadrillage-duel-contour">
                </div>
            </div>
            <div id="terrain-cadrillage-cimetiere">
                <span>Cimetière</span>
                <div id="terrain-cadrillage-cimetiere-contour">
                    
                </div>
            </div>
        </div>
    </div>
    )
}
    
ReactDOM.render(
    <Board />, 
    document.querySelector('#plateau')
);