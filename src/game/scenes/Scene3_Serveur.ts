import { Scene } from 'phaser';
import { DialogManager } from '../systems/DialogManager';
import { DialogUI } from '../ui/DialogUI';
import { UrgencyGauge } from '../ui/UrgencyGauge';
import { InventoryUI } from '../ui/InventoryUI';

export class Scene3_Serveur extends Scene {
    private dialogManager: DialogManager;
    private dialogUI: DialogUI;
    private urgencyGauge: UrgencyGauge;
    private inventoryUI: InventoryUI;

    private player: Phaser.GameObjects.Image;
    private boss: Phaser.GameObjects.Image;
    private projectiles: Phaser.GameObjects.Image[] = [];

    private bossHealth: number = 3;
    private isGameOver: boolean = false;
    private isGameStarted: boolean = false;

    private lastFired: number = 0;
    private fireRate: number = 1000; // ms

    constructor() {
        super('Scene3_Serveur');
    }

    create() {
        const { width, height } = this.scale;

        // Reset State explicitly on creation (fixes restart bug)
        this.bossHealth = 3;
        this.isGameOver = false;
        this.isGameStarted = false;
        this.projectiles = [];

        // Background
        this.add.image(width / 2, height / 2, 'bg_serveur').setDisplaySize(width, height);

        // Dialog System
        this.dialogManager = new DialogManager(this);
        this.dialogManager.loadDialogs('dialogs');
        this.dialogUI = new DialogUI(this);

        // Input handling for dialog
        this.input.on('pointerdown', (_pointer: any, gameObjects: any[]) => {
            if (this.dialogUI.visible) {
                if (this.dialogUI.isTyping) {
                    this.dialogUI.completeTyping();
                } else {
                    this.dialogManager.nextDialog();
                }
            } else if (this.isGameStarted && !this.isGameOver) {
                // Game input (if needed globally)
            }
        });
        this.dialogManager.on('dialog-update', (node: any) => {
            this.dialogUI.showDialog(node);
        });

        this.dialogManager.on('dialog-end', () => {
            this.dialogUI.hideDialog();

            // If the game hasn't started yet (intro dialog), start it.
            if (!this.isGameStarted && !this.isGameOver) {
                this.startGame();
            } else if (this.registry.get('scene3_complete')) {
                if (this.isGameOver && this.bossHealth <= 0) {
                    this.scene.start('MainMenu');
                }
            }
        });

        // Setup Boss (Wizart)
        this.boss = this.add.image(width / 2, height * 0.2, 'wizart_firewall');
        this.boss.setScale(0.8);
        this.boss.setInteractive();

        // Boss Click Interaction (Attack)
        this.boss.on('pointerdown', (pointer: any, localX: any, localY: any, event: any) => {
            if (this.isGameStarted && !this.isGameOver) {
                this.hitBoss();
                if (event && event.stopPropagation) event.stopPropagation();
            }
        });

        // Setup Player (Wiwi) - Follows mouse with lag
        this.player = this.add.image(width / 2, height * 0.8, 'wiwi');
        this.player.setScale(0.5);

        // Core Systems
        this.urgencyGauge = new UrgencyGauge(this);
        this.inventoryUI = new InventoryUI(this);
        this.inventoryUI.addItem('item_tchap', 'item_tchap');

        // Start Intro Dialog
        this.dialogManager.startDialog('scene3_intro');
    }

    private startGame() {
        this.isGameStarted = true;

        // Show instruction?
        const text = this.add.text(this.scale.width / 2, this.scale.height / 2, "ESQUIVE ET CLIQUE SUR WIZART !", {
            fontSize: '48px', color: '#ff0000', fontStyle: 'bold'
        }).setOrigin(0.5);
        this.tweens.add({ targets: text, alpha: 0, duration: 2000, onComplete: () => text.destroy() });
    }

    update(time: number, delta: number) {
        if (!this.isGameStarted || this.isGameOver) return;

        // 1. Player Movement (Even Slower/Laggier)
        const pointer = this.input.activePointer;
        // Reduced lerp from 0.05 to 0.02 for more "lag"
        this.player.x = Phaser.Math.Interpolation.Linear([this.player.x, pointer.x], 0.02);
        this.player.y = Phaser.Math.Interpolation.Linear([this.player.y, pointer.y], 0.02);

        // 2. Boss Rotation (Face Player)
        const angle = Phaser.Math.Angle.Between(this.boss.x, this.boss.y, this.player.x, this.player.y);
        this.boss.setRotation(angle + Math.PI / 2); // Adjust +90 deg if sprite is facing up, or 0 if right. Assuming up/front.

        // 3. Boss Logic (Shooting)
        if (time > this.lastFired + this.fireRate) {
            this.fireProjectile();
            this.lastFired = time;
        }

        // 4. Projectile Logic
        for (let i = this.projectiles.length - 1; i >= 0; i--) {
            const proj = this.projectiles[i];

            const vx = proj.getData('vx');
            const vy = proj.getData('vy');
            proj.x += vx;
            proj.y += vy;
            proj.rotation += 0.1; // Spin projectile

            // Check collision with player
            // Shrink hitbox slightly for fairness
            const playerBounds = this.player.getBounds();
            playerBounds.setSize(playerBounds.width * 0.5, playerBounds.height * 0.5);
            playerBounds.setPosition(this.player.x - playerBounds.width / 2, this.player.y - playerBounds.height / 2);

            if (Phaser.Geom.Intersects.RectangleToRectangle(proj.getBounds(), playerBounds)) {
                this.gameOver();
            }

            // Remove if out of bounds
            if (proj.y > this.scale.height + 50 || proj.y < -50 || proj.x < -50 || proj.x > this.scale.width + 50) {
                proj.destroy();
                this.projectiles.splice(i, 1);
            }
        }
    }

    private fireProjectile() {
        const proj = this.add.image(this.boss.x, this.boss.y, 'projectile');

        // Calculate vector to player
        const angle = Phaser.Math.Angle.Between(this.boss.x, this.boss.y, this.player.x, this.player.y);
        const speed = 8; // Slightly faster projectiles
        const vx = Math.cos(angle) * speed;
        const vy = Math.sin(angle) * speed;

        proj.setData('vx', vx);
        proj.setData('vy', vy);

        this.projectiles.push(proj);
    }

    private hitBoss() {
        this.bossHealth--;

        // Visual feedback
        this.tweens.add({
            targets: this.boss,
            alpha: 0.5,
            scale: 0.9,
            yoyo: true,
            duration: 100,
            repeat: 1
        });

        // Random Teleport/Movement
        const padding = 200;
        const newX = Phaser.Math.Between(padding, this.scale.width - padding);
        const newY = Phaser.Math.Between(100, this.scale.height * 0.4);

        this.tweens.add({
            targets: this.boss,
            x: newX,
            y: newY,
            duration: 500,
            ease: 'Power2'
        });

        if (this.bossHealth <= 0) {
            this.victory();
        }
    }

    private gameOver() {
        this.isGameOver = true;
        this.cameras.main.shake(200, 0.01);

        const text = this.add.text(this.scale.width / 2, this.scale.height / 2, "GAME OVER\nClique pour recommencer", {
            fontSize: '64px', color: '#ff0000', align: 'center', backgroundColor: '#000000'
        }).setOrigin(0.5).setDepth(100);

        this.input.once('pointerdown', () => {
            this.scene.restart();
        });
    }

    private victory() {
        this.isGameOver = true;
        // Clear projectiles
        this.projectiles.forEach(p => p.destroy());
        this.projectiles = [];

        this.registry.set('scene3_complete', true);
        this.dialogManager.startDialog('scene3_ending');
    }
}
