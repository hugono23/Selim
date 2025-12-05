import { Boot } from './scenes/Boot';
import { GameOver } from './scenes/GameOver';
import { Game as MainGame } from './scenes/Game';
import { MainMenu } from './scenes/MainMenu';
import { AUTO, Game } from 'phaser';
import { Preloader } from './scenes/Preloader';
import { Scene0_Intro } from './scenes/Scene0_Intro';
import { Scene1_Cimetiere } from './scenes/Scene1_Cimetiere';
import { Scene2_Atelier } from './scenes/Scene2_Atelier';
import { Scene3_Serveur } from './scenes/Scene3_Serveur';

//  Find out more information about the Game Config at:
//  https://docs.phaser.io/api-documentation/typedef/types-core#gameconfig
const config: Phaser.Types.Core.GameConfig = {
    type: AUTO,
    width: 2048,
    height: 1024,
    parent: 'game-container',
    backgroundColor: '#00ff2aff',
    scene: [
        Boot,
        Preloader,
        MainMenu,
        Scene0_Intro,
        Scene1_Cimetiere,
        Scene2_Atelier,
        Scene3_Serveur,
        MainGame,
        GameOver
    ]
};

const StartGame = (parent: string) => {

    return new Game({ ...config, parent });

}

export default StartGame;
